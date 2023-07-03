<?php

namespace App\Http\Requests\client\v1;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\client\v1\ActivityController;

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
            ActivityController::class    .  "@show"            => $this->show(),
        };

    }

    public function show(){
        request()->merge(['activity_slug' => $this->route('activity_slug')]);
        return [
            "activity_slug" => 'required|string|exists:activities,slug',
        ];
    }
}
