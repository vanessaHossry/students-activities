<?php

namespace App\Http\Requests\Client\V1;

use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Client\V1\AuthController             as V1ClientAuthController;
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
        $route_action= Route::current()->getActionName();
        return match($route_action) 
            {
                V1ClientAuthController::class    .  "@login"          => $this->login(),
            };
        
    }

    public function login(){
        return [
            'email' => 'required|email|string',
            'password' => ['required', 'string', Password::min(6)],
        ];
    }
}
