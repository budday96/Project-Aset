<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CostumUserModel;
use BaconQrCode\Renderer\Path\Move;
use Myth\Auth\Models\GroupModel;


class Profile extends BaseController
{

    protected $userModel;
    protected $groupModel;

    public function __construct()
    {
        $this->userModel = new CostumUserModel();
        $this->groupModel = new GroupModel();
    }

    public function index()
    {
        $userId = user_id(); // Myth Auth
        $user = $this->userModel->find($userId);

        // Ambil nama group
        $group = $this->groupModel->getGroupsForUser($userId);
        $groupName = $group[0]['name'] ?? 'user';

        return view('admin/profile/index', [
            'title' => 'Profile Saya',
            'user' => $user,
            'group_name' => $groupName,
        ]);
    }

    public function update()
    {
        $userId = user_id();
        $user = $this->userModel->find($userId);

        $validationRules = [
            'full_name' => 'required|string|min_length[3]|max_length[100]',
            'user_image' => [
                'label' => 'Gambar Profil',
                'rules' => 'is_image[user_image]|mime_in[user_image,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'is_image' => 'File harus berupa gambar',
                    'mime_in' => 'Hanya diperbolehkan JPG, JPEG, PNG'
                ]
            ]
        ];


        if (!$this->request->getFile('user_image')->isValid()) {
            unset($validationRules['user_image']);
        }

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fullName = $this->request->getPost('full_name');
        $updateData = ['full_name' => $fullName];

        $file = $this->request->getFile('user_image');
        if ($file && $file->isValid()) {
            $newFileName = $file->getRandomName();

            // Hapus file lama jika bukan default
            $oldPath = FCPATH . 'img/' . $user->user_image;
            if ($user->user_image && $user->user_image !== 'default.jpg' && file_exists($oldPath) && is_file($oldPath)) {
                unlink($oldPath);
            }

            // Simpan file baru
            $file->move(FCPATH . 'img', $newFileName);
            $updateData['user_image'] = $newFileName;
        }

        $this->userModel->update($userId, $updateData);

        return redirect()->to('/admin/profile')->with('success', 'Profil berhasil diperbarui');
    }
}
