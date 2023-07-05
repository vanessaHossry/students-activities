<?php

namespace App\Http\Requests\admin\v1;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\admin\v1\ActivityController;

class ActivityRequest extends FormRequest
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
            ActivityController::class    .  "@store"           => $this->store(),
            ActivityController::class    .  "@show"            => $this->show(),
            ActivityController::class    .  "@update"          => $this->update(),
            ActivityController::class    .  "@destroy"         => $this->destroy(),
            ActivityController::class    .  "@restore"         => $this->restore(),
            ActivityController::class    .  "@deactivate"      => $this->deactivate(),
            ActivityController::class    .  "@activate"        => $this->activate(),
        };
    }
    public function store(){
        return [
            "name" => "required|string|unique:activities,name",
            "price" =>"required|numeric",
        ];
    }

    public function show(){
        request()->merge(['activity_slug' => $this->route('activity_slug')]);
        return [
            "activity_slug" => 'required|string|exists:activities,slug',
        ];
    }

    public function update(){
        request()->merge(['activity_slug' => $this->route('activity_slug')]);
        return [
           // "activity_slug" => 'required|string|exists:activities,slug,deleted_at,NULL',  it works if i don't want to display that the act is deleted
           "activity_slug" => 'required|string|exists:activities,slug', 
           "price" => 'required|numeric',
        ];
    }

    
    public function destroy(){
        request()->merge(['activity_slug' => $this->route('activity_slug')]);
        return [
            "activity_slug" => 'required|string|exists:activities,slug',
        ];
    }

    public function restore(){
        request()->merge(['activity_slug' => $this->route('activity_slug')]);
        return [
            "activity_slug" => 'required|string|exists:activities,slug',
        ];
    }

    public function deactivate(){
        request()->merge(['activity_slug' => $this->route('activity_slug')]);
        return [
            "activity_slug" => 'required|string|exists:activities,slug',
        ];
    }

    public function activate(){
        request()->merge(['activity_slug' => $this->route('activity_slug')]);
        return [
            "activity_slug" => 'required|string|exists:activities,slug',
        ];
    }
}
