<?php

namespace App\Repositories;
use App\Models\Role;
use App\Interfaces\RoleInterface;

class RoleRepository implements RoleInterface
{
    public function getRoles(){
        $roles = Role::paginate(10);
        return $roles;
    }
}
