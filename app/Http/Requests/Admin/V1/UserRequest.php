<?php

namespace App\Http\Requests\admin\v1;

use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Admin\V1\UserController;

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
        $route_action = Route::current()->getActionName();
        return match($route_action){
            UserController::class .  '@store'             => $this ->store(),
            UserController::class .  '@show'              => $this ->show(),
            UserController::class .  '@destroy'           => $this ->destroy(),
            UserController::Class .  '@index'             => $this ->index(),
            UserController::Class .  '@blockUser'         => $this ->blockUser(),
        };
    }

    public function store()
    {
        return [
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            'email'         => 'required|email|string|unique:users,email',
            'password'      => ['required', 'string', Password::min(6)],
            'date_of_birth' => 'required|date|before:2002-01-01',
            'gender'        => 'string|nullable',
         // 'portal_slug'   => ['required','string',Rule::in(['admin','user'])],
            'role_slug'     => ['required','string',Rule::in(['super-admin','user','tutor'])],
        ];
    }

    public function show()
    {
        request()->merge(['email' => $this->route('email')]);
        return [
             'email'       => 'required|email|string|exists:users',     
        ];
    }

    public function destroy(){
        request()->merge(['email' => $this->route('email')]);
        return [
            'email'         => 'required|string|email|exists:users',
        ];
    }

    public function index(){
        request()->merge(['name' => $this->query('name'),
                          'gender' => $this->query('gender'),
                          'per_page' => $this->query('per_page')]);
        return [
            'name'          => 'nullable|string',
            'gender'        => ['nullable','string',Rule::in(['female','male'])],
            'per_page'      => 'required|max:100',
            'role'          => ['nullable','string',Rule::in(['super-admin','user','tutor'])],
        ];
    }

    public function blockUser(){
        request()->merge(['email' => $this->route('email')]);
        return [
            'email'         => 'required|string|email|exists:users',
        ];
    }
}
