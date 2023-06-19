<?php

namespace App\Http\Requests\Client\V1;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Client\V1\UserController           as V1ClientUserController;
class UserRequest extends FormRequest
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
        return match($route_action) {
            V1ClientUserController::class . '@signUp'            => $this->signUp(),
        };
    }

    public function signUp()
    {
        return [
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            'email'         => 'required|email|string|unique:users,email',
            'password'      => ['required', 'string', Password::min(6)],
            'date_of_birth' => 'required|date|before:2002-01-01',
            'gender'        => 'string|nullable',
            'role_slug'     => ['required','string',Rule::in(['user','tutor'])],
        ];
    }
}
