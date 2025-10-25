<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\CostumUserModel as UserModel;
use App\Models\CabangModel;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Password;

class UserManagement extends BaseController
{
    protected $userModel;
    protected $groupModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->groupModel = new GroupModel();
    }

    public function index()
    {
        $users = $this->userModel
            ->select('users.*, ag.name as group_name, cabang.nama_cabang')
            ->join('auth_groups_users agu', 'agu.user_id = users.id', 'inner')
            ->join('auth_groups ag', 'ag.id = agu.group_id', 'left')
            ->join('cabang', 'cabang.id_cabang = users.id_cabang', 'left')
            ->where('ag.name !=', 'superadmin')
            ->orderBy('users.id', 'DESC')
            ->findAll();

        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('users.id');
        $builder->join('auth_groups_users agu', 'agu.user_id = users.id', 'left');
        $builder->where('agu.group_id IS NULL', null, false);

        $pendingCount = $builder->countAllResults();

        return view('superadmin/user/index', [
            'title' => 'Manajemen Pengguna',
            'users' => $users,
            'pendingCount' => $pendingCount
        ]);
    }

    public function create()
    {
        $groups = $this->groupModel->findAll();
        $cabangModel = new CabangModel();
        $cabang = $cabangModel->findAll();
        return view('superadmin/user/create', [
            'title'  => 'Tambah User',
            'groups' => $groups,
            'cabang' => $cabang
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();

        if (!$this->validate([
            'full_name' => 'required',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'username' => 'required|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'role'     => 'required',
            'id_cabang' => 'required|is_not_unique[cabang.id_cabang]',

        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = $this->userModel->insert([
            'email'         => $data['email'],
            'username'      => $data['username'],
            'full_name'     => $data['full_name'],
            'password_hash' => Password::hash($data['password']),
            'id_cabang' => $data['id_cabang'],
            'active'        => 1,
        ]);

        $this->groupModel->addUserToGroup($userId, $data['role']);

        return redirect()->to('/superadmin/user')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id = null)
    {
        if (empty($id)) {
            return redirect()->to('/superadmin/user')->with('error', 'Parameter tidak valid');
        }

        $group = $this->groupModel->getGroupsForUser($id);
        if (!empty($group) && $group[0]['name'] === 'superadmin') {
            return redirect()->to('/superadmin/user')->with('error', 'Superadmin tidak boleh diedit');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        $groups = $this->groupModel->findAll();
        $userGroups = $this->groupModel->getGroupsForUser($id);
        $userGroupId = $userGroups[0]['group_id'] ?? null;

        $cabangModel = new CabangModel();
        $cabang = $cabangModel->findAll();

        return view('superadmin/user/edit', [
            'title'     => 'Edit User',
            'user'      => $user,
            'groups'    => $groups,
            'userGroup' => $userGroupId,
            'cabang' => $cabang
        ]);
    }

    public function update($id)
    {
        $data = $this->request->getPost();

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        $rules = [
            'full_name' => 'required',
        ];

        // Validasi email jika berubah
        if ($user->email !== $data['email']) {
            $rules['email'] = 'required|valid_email|is_unique[users.email]';
        } else {
            $rules['email'] = 'required|valid_email';
        }

        // Validasi username jika berubah
        if ($user->username !== $data['username']) {
            $rules['username'] = 'required|is_unique[users.username]';
        } else {
            $rules['username'] = 'required';
        }

        // Validasi role hanya jika belum ada
        $userGroups = $this->groupModel->getGroupsForUser($id);
        if (empty($userGroups)) {
            $rules['role'] = 'required';
        }

        // Validasi cabang hanya jika belum di-set
        if (empty($user->id_cabang)) {
            $rules['id_cabang'] = 'required|is_not_unique[cabang.id_cabang]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'full_name' => $data['full_name'],
            'email'     => $data['email'],
            'username'  => $data['username'],
        ];

        // Hanya isi jika sebelumnya belum di-set
        if (empty($user->id_cabang)) {
            $updateData['id_cabang'] = $data['id_cabang'];
        }

        // Password opsional
        if (!empty($data['password'])) {
            $updateData['password_hash'] = Password::hash($data['password']);
        }

        // Simpan perubahan hanya jika ada yang berubah
        $user->fill($updateData);
        if ($user->hasChanged()) {
            $this->userModel->save($user);
        }

        // Set grup hanya jika belum ada
        if (empty($userGroups) && !empty($data['role'])) {
            $this->groupModel->addUserToGroup($id, $data['role']);
        }

        return redirect()->to('/superadmin/user')->with('success', 'User berhasil diperbarui');
    }


    public function detail($id = null)
    {
        if (empty($id)) {
            return redirect()->to('/superadmin/user')->with('error', 'Parameter tidak valid');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        // Ambil grup user (join ke auth_groups)
        $db = \Config\Database::connect();
        $builder = $db->table('auth_groups_users')
            ->select('auth_groups.name, auth_groups.description')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->where('auth_groups_users.user_id', $id)
            ->get()
            ->getRow();

        $groupName = $builder->name ?? 'Tidak diketahui';

        return view('superadmin/user/detail', [
            'title'      => 'Detail User',
            'user'       => $user,
            'group_name' => $groupName
        ]);
    }

    public function delete($id)
    {
        $group = $this->groupModel->getGroupsForUser($id);
        if (!empty($group) && $group[0]['name'] === 'superadmin') {
            return redirect()->to('/superadmin/user')->with('error', 'Superadmin tidak boleh dihapus');
        }

        $this->groupModel->removeUserFromAllGroups($id);
        $this->userModel->delete($id);

        return redirect()->to('/superadmin/user')->with('success', 'User berhasil dihapus');
    }


    public function toggleActive($id)
    {
        $user = $this->userModel->find($id);
        if ($user) {
            $this->userModel->update($id, [
                'active' => $user->active ? 0 : 1
            ]);
        }

        return redirect()->to('/superadmin/user')->with('success', 'Status user diperbarui');
    }
}
