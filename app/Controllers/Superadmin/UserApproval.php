<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Models\CostumUserModel;
use Myth\Auth\Models\GroupModel;
use App\Models\CabangModel;

class UserApproval extends BaseController
{
    protected $userModel;
    protected $groupModel;
    protected $cabangModel;

    public function __construct()
    {
        $this->userModel = new CostumUserModel();
        $this->groupModel  = new GroupModel();
        $this->cabangModel = new CabangModel();
    }

    public function index()
    {
        $users = $this->userModel
            ->select('users.*')
            ->join('auth_groups_users agu', 'agu.user_id = users.id', 'left')
            ->where('agu.group_id IS NULL', null, false)
            ->orderBy('users.id', 'DESC')
            ->findAll();

        $cabang = $this->cabangModel->findAll();

        return view('superadmin/user_approval/index', [
            'title' => 'User Approval',
            'users' => $users,
            'cabang' => $cabang
        ]);
    }

    public function setRole($id)
    {
        $data = $this->request->getPost();
        $role = $data['role'] ?? null;
        $idCabang = $data['id_cabang'] ?? null;

        if (!$role || !$idCabang) {
            return redirect()->back()->with('error', 'Role dan Cabang harus dipilih');
        }

        $group = $this->groupModel->where('name', $role)->first();
        if (!$group) {
            return redirect()->back()->with('error', 'Role tidak valid');
        }

        // Cegah jika sudah punya role
        $db = \Config\Database::connect();
        $cek = $db->table('auth_groups_users')->where('user_id', $id)->get()->getRow();
        if ($cek) {
            return redirect()->back()->with('error', 'User sudah memiliki role');
        }

        // Tetapkan role
        $this->groupModel->addUserToGroup($id, $group->id);

        // Simpan cabang ke user
        $this->userModel->update($id, [
            'id_cabang' => $idCabang
        ]);

        return redirect()->back()->with('success', 'Role dan Cabang berhasil ditetapkan');
    }
}
