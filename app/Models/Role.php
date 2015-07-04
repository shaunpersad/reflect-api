<?php


namespace App\Models;


use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole {

    const ROLE_ADMIN = 'admin';
    const ROLE_SUPER_ADMIN = 'super_admin';
} 