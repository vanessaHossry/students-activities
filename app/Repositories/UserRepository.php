<?php

namespace App\Repositories;
use App\Models\Role;
use App\Models\User;
use App\Interfaces\UserInterface;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserInterface
{
    public function getSelf()
    {
        return Auth::user();
    }

    public function store($request)
    {
        $user = User::create([
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "email" => $request->email,
            "password" => $request->password,
            "date_of_birth" => $request->date_of_birth,
            "gender" => $request->gender,

        ]);
        $role_id = Role::where("slug","super-admin")->value('id');
        $user->assignRole($role_id);

        return $user;
    }
}
