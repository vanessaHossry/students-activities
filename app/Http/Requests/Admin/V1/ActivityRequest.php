<?php

namespace App\Http\Requests\admin\V1;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Admin\V1\ActivitiesController;

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
        Log::info($route_action);
        return match($route_action)
        {
            ActivitiesController::class    .  "@store"           => $this->store(),
            ActivitiesController::class    .  "@update"          => $this->update(),
        };
    }
    public function store()
    {
        return [
            "activity_slug" => 'required|string|exists:activities,slug',
            "weekday" => 'required|string|exists:week_days,slug',
            "start_time" => 'required|date_format:H:i',
            "end_time" => 'required|date_format:H:i|after:start_time',
        ];
    }

    public function update()
    {

        request()->merge(['activity_slug' => $this->route('activity_slug')]);
        return [
            "activity_slug" => 'required|string|exists:activities,slug',
            "weekday.*" => 'required|string|exists:week_days,slug',
            "start_time" => 'required|date_format:H:i',
            "end_time" => 'required|date_format:H:i|after:start_time',
        ];
    }
}
