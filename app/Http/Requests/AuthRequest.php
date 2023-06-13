<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Admin\V1\AuthController;

class AuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $route_action = Route::current()->getActionName();
        return match($route_action){
                AuthController::class  .  "@login"          => $this->login(),
                AuthController::class  .  "@store"          => $this->store(),  
        };
    }

    public function login(){
        return [
            'email' => 'required|email',
            'password' => ['required', 'string', Password::min(6)],
        ];
    }

    public function store(){
        return [
            'first_name'     => 'required|string',
            'last_name'      => 'required|string',
            'email'          => 'required|email|unique:users,email',
            'password'       => ['required','string', Password::min(6)],  
            'date_of_birth'  => 'date',
            'gender'         => 'string|nullable',
             ];
    }
}
