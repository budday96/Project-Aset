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
    protected $adminCabangId;   // cabang milik admin yang login
    // pakai name yang stabil
    private string $defaultRoleName = 'user';
    private ?int $defaultRoleId = null; // cache

    public function __construct()
    {
        $this->userModel  = new UserModel();
        $this->groupModel = new GroupModel();
    }

    protected function getAdminCabangId(): ?int
    {
        // asumsi helper user() aktif (Myth\Auth)
        return user()->id_cabang ?? null;
    }

    private function getDefaultRoleId(): int
    {
        if ($this->defaultRoleId !== null) {
            return $this->defaultRoleId;
        }
        $role = $this->groupModel->select('id')->where('name', $this->defaultRoleName)->first();
        if (!$role || empty($role->id)) {
            // tangani rapi kalau seed group belum ada
            throw new \RuntimeException('Grup "user" tidak ditemukan. Seed auth_groups terlebih dulu.');
        }
        return $this->defaultRoleId = (int) $role->id;
    }

    protected function ensureSameBranchOrAbort($targetUser): void
    {
        if (!$targetUser || (int)$targetUser->id_cabang !== (int)$this->adminCabangId) {
            // Admin hanya boleh kelola user di cabangnya
            // Ubah redirect tujuan jika path Anda berbeda
            redirect()->to('/admin/user')->with('error', 'Tidak diizinkan: berbeda cabang')->send();
            exit; // pastikan stop flow
        }
    }

    public function index()
    {
        $this->adminCabangId = $this->getAdminCabangId();

        // Tampilkan hanya user di cabang admin & sembunyikan admin/superadmin
        $users = $this->userModel
            ->select('users.*, ag.name as group_name')
            ->join('auth_groups_users agu', 'agu.user_id = users.id', 'inner')
            ->join('auth_groups ag', 'ag.id = agu.group_id', 'left')
            ->where('users.id_cabang', $this->adminCabangId)
            ->whereNotIn('ag.name', ['admin', 'superadmin']) // admin tidak boleh kelola role ini
            ->orderBy('users.id', 'DESC')
            ->findAll();

        // hitung pending (user tanpa grup) tapi hanya di cabang admin
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('users.id');
        $builder->join('auth_groups_users agu', 'agu.user_id = users.id', 'left');
        $builder->where('users.id_cabang', $this->adminCabangId);
        $builder->where('agu.group_id IS NULL', null, false);
        $pendingCount = $builder->countAllResults();

        return view('admin/user/index', [
            'title'        => 'Manajemen Pengguna Cabang',
            'users'        => $users,
            'pendingCount' => $pendingCount
        ]);
    }

    public function create()
    {
        $this->adminCabangId = $this->getAdminCabangId();

        // Admin tidak memilih cabang & role—dikunci otomatis
        return view('admin/user/create', [
            'title'       => 'Tambah User Cabang',
            'cabang_name' => user()->nama_cabang ?? '-', // opsional untuk ditampilkan saja
        ]);
    }

    public function store()
    {
        $this->adminCabangId = $this->getAdminCabangId();
        $data = $this->request->getPost();

        // VALIDASI: tanpa role & id_cabang (dikunci)
        if (!$this->validate([
            'full_name' => 'required',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'username'  => 'required|is_unique[users.username]',
            'password'  => 'required|min_length[6]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // insert user ke cabang admin
        $userId = $this->userModel->insert([
            'email'         => $data['email'],
            'username'      => $data['username'],
            'full_name'     => $data['full_name'],
            'password_hash' => Password::hash($data['password']),
            'id_cabang'     => $this->adminCabangId,
            'active'        => 1,
        ]);

        // pakai ID yang sudah di-cache
        $this->groupModel->addUserToGroup($userId, $this->getDefaultRoleId());

        return redirect()->to('/admin/user')->with('success', 'User cabang berhasil ditambahkan');
    }

    public function edit($id = null)
    {
        if (empty($id)) {
            return redirect()->to('/admin/user')->with('error', 'Parameter tidak valid');
        }

        $this->adminCabangId = $this->getAdminCabangId();

        $user = $this->userModel->find($id);
        $this->ensureSameBranchOrAbort($user);

        // Cegah edit user dengan role admin/superadmin
        $groups = $this->groupModel->getGroupsForUser($id);
        $roleName = $groups[0]['name'] ?? null;
        if (in_array($roleName, ['admin', 'superadmin'])) {
            return redirect()->to('/admin/user')->with('error', 'Tidak diizinkan mengubah akun admin/superadmin');
        }

        // View admin tidak boleh mengubah role & cabang
        return view('admin/user/edit', [
            'title' => 'Edit User Cabang',
            'user'  => $user,
        ]);
    }

    public function update($id)
    {
        $this->adminCabangId = $this->getAdminCabangId();
        $data = $this->request->getPost();

        $user = $this->userModel->find($id);
        $this->ensureSameBranchOrAbort($user);

        $groups = $this->groupModel->getGroupsForUser($id);
        $roleName = $groups[0]['name'] ?? null;
        if (in_array($roleName, ['admin', 'superadmin'])) {
            return redirect()->to('/admin/user')->with('error', 'Tidak diizinkan mengubah akun admin/superadmin');
        }

        $rules = [
            'full_name' => 'required',
        ];

        // Email/username unique jika berubah
        $rules['email']    = ($user->email !== $data['email']) ? 'required|valid_email|is_unique[users.email]' : 'required|valid_email';
        $rules['username'] = ($user->username !== $data['username']) ? 'required|is_unique[users.username]' : 'required';

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'full_name' => $data['full_name'],
            'email'     => $data['email'],
            'username'  => $data['username'],
            // 'id_cabang' TIDAK diizinkan berubah oleh admin
        ];

        if (!empty($data['password'])) {
            $updateData['password_hash'] = Password::hash($data['password']);
        }

        $user->fill($updateData);
        if ($user->hasChanged()) {
            $this->userModel->save($user);
        }

        // Role tidak diubah oleh admin
        return redirect()->to('/admin/user')->with('success', 'User cabang berhasil diperbarui');
    }

    public function detail($id = null)
    {
        if (empty($id)) {
            return redirect()->to('/admin/user')->with('error', 'Parameter tidak valid');
        }

        $this->adminCabangId = $this->getAdminCabangId();
        $user = $this->userModel->find($id);
        $this->ensureSameBranchOrAbort($user);

        $db = \Config\Database::connect();
        $builder = $db->table('auth_groups_users')
            ->select('auth_groups.name, auth_groups.description')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->where('auth_groups_users.user_id', $id)
            ->get()
            ->getRow();

        $groupName = $builder->name ?? 'Tidak diketahui';

        return view('admin/user/detail', [
            'title'      => 'Detail User Cabang',
            'user'       => $user,
            'group_name' => $groupName
        ]);
    }

    public function delete($id)
    {
        $this->adminCabangId = $this->getAdminCabangId();

        $user = $this->userModel->find($id);
        $this->ensureSameBranchOrAbort($user);

        $groups = $this->groupModel->getGroupsForUser($id);
        $roleName = $groups[0]['name'] ?? null;
        if (in_array($roleName, ['admin', 'superadmin'])) {
            return redirect()->to('/admin/user')->with('error', 'Tidak diizinkan menghapus admin/superadmin');
        }

        $this->groupModel->removeUserFromAllGroups($id);
        $this->userModel->delete($id);

        return redirect()->to('/admin/user')->with('success', 'User cabang berhasil dihapus');
    }

    public function toggleActive($id)
    {
        $this->adminCabangId = $this->getAdminCabangId();

        $user = $this->userModel->find($id);
        $this->ensureSameBranchOrAbort($user);

        $groups = $this->groupModel->getGroupsForUser($id);
        $roleName = $groups[0]['name'] ?? null;
        if (in_array($roleName, ['admin', 'superadmin'])) {
            return redirect()->to('/admin/user')->with('error', 'Tidak diizinkan mengubah status admin/superadmin');
        }

        $this->userModel->update($id, ['active' => $user->active ? 0 : 1]);

        return redirect()->to('/admin/user')->with('success', 'Status user cabang diperbarui');
    }
}
