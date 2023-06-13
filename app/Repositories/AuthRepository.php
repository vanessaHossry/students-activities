<?php

namespace App\Repositories;
use Exception;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\AuthRepositoryInterface;


class AuthRepository implements AuthRepositoryInterface
{
    use ApiResponse;
    public function login( $request)
    {

        // $credentials = $request->all();
        $credentials = request(['email', 'password']);
        $token = auth()->attempt($credentials);

        return $token;
            

    }
    public function store( $request)
    {

            $user = User::create([
                "first_name" => $request->first_name,
                "last_name" => $request->last_name,
                "email" => $request->email,
                "password" => $request->password,
                "date_of_birth" => $request->date_of_birth,
                "gender" => $request->gender,

            ]);

         
            return $user;

    }
    

    public function me()
    {
        return Auth::user();
    }


}
