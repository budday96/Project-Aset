<?php

namespace App\Models;

use Myth\Auth\Models\UserModel as MythUserModel;

class CostumUserModel extends MythUserModel
{
    protected $allowedFields = [
        'email',
        'username',
        'password_hash',
        'reset_hash',
        'reset_at',
        'reset_expires',
        'activate_hash',
        'status',
        'status_message',
        'active',
        'force_pass_reset',
        'user_image',
        'full_name',
        'id_cabang',
    ];
}
