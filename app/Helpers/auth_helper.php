<?php
function get_user_role_name()
{
    if (in_groups('superadmin')) {
        return 'Superadmin';
    }
    if (in_groups('admin')) {
        return 'Admin Cabang';
    }
    return 'User';
}
