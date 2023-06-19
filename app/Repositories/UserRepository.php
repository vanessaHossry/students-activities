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
        $role_id = Role::where("slug", $request->role_slug)->value('id');

        $user->assignRole($role_id);

        return $user;
    }

    public function index()
    {
        //return User::all();
          return User::get();
    }

    public function show($request)
    {
        return User::where('email', $request->email)->first();
    }

    public function getDeleted(){
        // $users = User::whereNotNull('deleted_at')->withTrashed()->get();

        //-- this query only returns DELETED users
        $users = User::onlyTrashed()->get();

        //-- this query returns all the users (DELETED + NOT DELETED)
        $users = User::withTrahsed()->get();

        //-- this query returns NOT DELETED users
        $users = User::get();        

        return $users;
    }

    public function getUserByEmail($email){
        $user = User::where('email',$email)->first();
        return $user;
    }
    public function getRoleByEmail($email){

        $userRole = User::where('email',$email)->with('roles')->first();
     
        return $userRole['roles']->value('name');


        //  $user = User::where('email',$email)
        // ->whereHas('roles', function($query){
        // $query->where('name', 'Super Admin');
        // })->first();
    }
}
