<?php

namespace App\Http\Requests\Client\V1;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Client\V1\UserController ;         
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
            UserController::class . '@signUp'            => $this->signUp(),
            UserController::class . '@updateImages'  => $this->updateImages(),
            UserController::class . '@forgotPassword'      => $this->forgotPassword(),
            UserController::class . '@resetPassword'     => $this->resetPassword(),

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
            'image'         => 'nullable|image|file|max:512'
            //'role_slug'     => ['required','string',Rule::in(['user','tutor'])],
        ];
    }

    public function updateImages(){
        return[
            'image'=> 'nullable|image|file|max:512'
        ];
    }

    public function forgotPassword(){
        return [
            'email'                => 'required|email|string|exists:users,email',
        //     'old'               => 'password' => 'current_password:api',
 
    ];
    }

    public function resetPassword(){
        return[  
        'token'                  => 'required|string',
        'email'                  => 'required|string|email|exists:users,email',
        'password'               =>  ['required','string', Password::min(6), 'confirmed'],
        'password_confirmation'  =>  ['required'],
        ];
    }
}
