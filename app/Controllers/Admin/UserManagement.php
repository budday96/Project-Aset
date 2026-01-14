<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CostumUserModel as UserModel;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Password;

class UserManagement extends BaseController
{
    protected $userModel;
    protected $groupModel;

    public function __construct()
    {
        $this->userModel  = new UserModel();
        $this->groupModel = new GroupModel();
    }

    /**
     * List user hanya di cabang admin
     */
    public function index()
    {
        $idCabang = user()->id_cabang;

        $users = $this->userModel
            ->select('users.*, ag.name as group_name')
            ->join('auth_groups_users agu', 'agu.user_id = users.id', 'inner')
            ->join('auth_groups ag', 'ag.id = agu.group_id', 'inner')
            ->where('users.id_cabang', $idCabang)
            ->where('ag.name', 'user') // ⬅️ HANYA USER BIASA
            ->orderBy('users.id', 'DESC')
            ->findAll();

        return view('admin/user/index', [
            'title' => 'Manajemen User Cabang',
            'users' => $users
        ]);
    }


    /**
     * Form tambah user cabang
     */
    public function create()
    {
        return view('admin/user/create', [
            'title' => 'Tambah User Cabang'
        ]);
    }

    /**
     * Simpan user baru (langsung aktif, tanpa approval)
     */
    public function store()
    {
        $data = $this->request->getPost();

        if (!$this->validate([
            'full_name' => 'required',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'username'  => 'required|is_unique[users.username]',
            'password'  => 'required|min_length[6]',
        ])) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userId = $this->userModel->insert([
            'email'         => $data['email'],
            'username'      => $data['username'],
            'full_name'     => $data['full_name'],
            'password_hash' => Password::hash($data['password']),
            'id_cabang'     => user()->id_cabang, // ⬅ AUTO CABANG
            'active'        => 1,                  // ⬅ LANGSUNG AKTIF
        ]);

        // Role default user cabang
        $group = $this->groupModel
            ->where('name', 'user')
            ->first();

        if ($group) {
            $this->groupModel->addUserToGroup($userId, $group->id);
        }


        return redirect()->to('/admin/user')
            ->with('success', 'User cabang berhasil ditambahkan');
    }

    /**
     * Edit user cabang (tidak boleh lintas cabang)
     */
    public function edit($id = null)
    {
        if (empty($id)) {
            return redirect()->to('/admin/user')->with('error', 'Parameter tidak valid');
        }

        $user = $this->userModel->find($id);

        if (!$user || $user->id_cabang != user()->id_cabang) {
            return redirect()->to('/admin/user')->with('error', 'Akses ditolak');
        }

        return view('admin/user/edit', [
            'title' => 'Edit User Cabang',
            'user'  => $user
        ]);
    }

    /**
     * Update user cabang
     */
    public function update($id)
    {
        $data = $this->request->getPost();
        $user = $this->userModel->find($id);

        if (!$user || $user->id_cabang != user()->id_cabang) {
            return redirect()->to('/admin/user')->with('error', 'Akses ditolak');
        }

        $rules = [
            'full_name' => 'required',
        ];

        if ($user->email !== $data['email']) {
            $rules['email'] = 'required|valid_email|is_unique[users.email]';
        } else {
            $rules['email'] = 'required|valid_email';
        }

        if ($user->username !== $data['username']) {
            $rules['username'] = 'required|is_unique[users.username]';
        } else {
            $rules['username'] = 'required';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'full_name' => $data['full_name'],
            'email'     => $data['email'],
            'username'  => $data['username'],
        ];

        if (!empty($data['password'])) {
            $updateData['password_hash'] = Password::hash($data['password']);
        }

        $user->fill($updateData);
        if ($user->hasChanged()) {
            $this->userModel->save($user);
        }

        return redirect()->to('/admin/user')
            ->with('success', 'User berhasil diperbarui');
    }

    public function detail($id = null)
    {
        if (empty($id)) {
            return redirect()->to('/admin/user')->with('error', 'Parameter tidak valid');
        }

        $user = $this->userModel->find($id);

        // Proteksi cabang + data
        if (
            !$user ||
            $user->id_cabang != user()->id_cabang
        ) {
            return redirect()->to('/admin/user')->with('error', 'Akses ditolak');
        }

        // Ambil group user
        $groups = $this->groupModel->getGroupsForUser($id);
        $groupName = $groups[0]['name'] ?? 'Tidak diketahui';

        // Admin hanya boleh lihat detail USER biasa
        if ($groupName !== 'user') {
            return redirect()->to('/admin/user')->with(
                'error',
                'Anda tidak memiliki akses ke detail akun ini'
            );
        }

        return view('admin/user/detail', [
            'title'      => 'Detail User Cabang',
            'user'       => $user,
            'group_name' => $groupName
        ]);
    }


    /**
     * Hapus user cabang
     */
    public function delete($id)
    {
        $user = $this->userModel->find($id);

        if (
            !$user ||
            $user->id_cabang != user()->id_cabang ||
            $user->id == user()->id
        ) {
            return redirect()->to('/admin/user')->with('error', 'Akses ditolak');
        }

        // Ambil group user
        $groups = $this->groupModel->getGroupsForUser($id);

        // Pastikan hanya user biasa
        if (empty($groups) || $groups[0]['name'] !== 'user') {
            return redirect()->to('/admin/user')->with(
                'error',
                'Anda hanya dapat menghapus user biasa'
            );
        }

        $this->groupModel->removeUserFromAllGroups($id);
        $this->userModel->delete($id);

        return redirect()->to('/admin/user')
            ->with('success', 'User berhasil dihapus');
    }


    /**
     * Toggle aktif / nonaktif user cabang
     */
    public function toggleActive($id)
    {
        $user = $this->userModel->find($id);

        if (
            !$user ||
            $user->id_cabang != user()->id_cabang ||
            $user->id == user()->id
        ) {
            return redirect()->to('/admin/user')->with('error', 'Akses ditolak');
        }

        // Ambil group user
        $groups = $this->groupModel->getGroupsForUser($id);

        // Pastikan hanya user biasa
        if (empty($groups) || $groups[0]['name'] !== 'user') {
            return redirect()->to('/admin/user')->with(
                'error',
                'Anda hanya dapat mengubah status user biasa'
            );
        }

        $this->userModel->update($id, [
            'active' => $user->active ? 0 : 1
        ]);

        return redirect()->to('/admin/user')
            ->with('success', 'Status user diperbarui');
    }
}
