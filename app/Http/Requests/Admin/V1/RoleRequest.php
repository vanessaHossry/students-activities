<?php

namespace App\Http\Requests\admin\v1;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\admin\v1\RoleController;

class RoleRequest extends FormRequest
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
            RoleController::class    .  "@givePermissionToUser"           => $this->givePermission(),
            //RoleController::class    .  "@show"            => $this->assign(),
            
        };
    }

    public function givePermission(){
        request()->merge(['user_email' => $this->route('user_email'), 'permission' => $this->route('permission')]);
        return[
            "user_email"        =>  'required|email|exists:users,email',
            "permission"   =>  'required|string|exists:permissions,name'
        ];
        
    }
   
}
