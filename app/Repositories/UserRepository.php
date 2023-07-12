<?php

namespace App\Repositories;
use App\Models\Role;
use App\Models\User;
use App\Models\Image;
use App\Models\Portal;
use App\Traits\utilities;
use App\Interfaces\UserInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserInterface
{
    use utilities;
    public function getSelf()
    {
        return Auth::user();
    }

    public function store($request)
    {
        if (Auth::check()) {
            // $email = Auth::user()->email;
            // if(isset($email)){
            //     $role_name = $this->getRoleByEmail($email);
            //}
            $role_name = "Super Admin";
        } else
            $role_name = "User";

        if ($role_name == "Super Admin") {
            $role = Role::where("slug", $request->role_slug)->first();
            $role_id = $role->id;
            $portal_id = $role->portal_id;
        } else {
            $role = Role::where("slug", "user")->first();
            $role_id = $role->id;
            $portal_id = $role->portal_id;
        }

        $img = $this->generateImageURL($request);

        $user = User::create([
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "email" => $request->email,
            "password" => $request->password,
            "date_of_birth" => $request->date_of_birth,
            "gender" => $request->gender,
            "portal_id" => $portal_id,

        ]);
        $userID = $user->id;

        Image::create([
            "source" => $img,
            "user_id" => $userID,
        ]);

        $user->assignRole($role_id);

        return $user;
    }

    public function index($request)
    {   
        $users = User::query();
        
        if (isset($request->name)) {
            //without the wrapping record kevin was still showing with female filter
            $users = $users->where(function ($query) use ($request) {
                $query->where('first_name', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $request->name . '%');
            });
      }
        if(isset($request->gender)){
        $users = $users->where('gender',$request->gender);
      }
        if(isset($request->role)){
        $users = $users->whereHas('roles', function ($query) use ($request) {
            $query->where('slug', $request->role);
        });
        }

        return $users->active()->paginate($request->per_page);
}

    public function show($request)
    {
        return User::where('email', $request->email)->first();
    }

    public function getDeleted(){
        // $users = User::whereNotNull('deleted_at')->withTrashed()->get();

        //-- this query returns all the users (DELETED + NOT DELETED)
        $users = User::withTrashed()->get();

        //-- this query returns NOT DELETED users
        $users = User::get();   
        
        //-- this query only returns DELETED users
        $users = User::onlyTrashed()->get();


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
