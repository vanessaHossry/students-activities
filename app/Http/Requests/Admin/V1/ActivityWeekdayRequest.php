<?php

namespace App\Http\Requests\admin\V1;

use App\Http\Controllers\admin\v1\ActivityController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Admin\V1\ActivitiesWeekdaysController;

class ActivityWeekdayRequest extends FormRequest
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
            ActivitiesWeekdaysController::class    .  "@store"           => $this->store(),
            ActivitiesWeekdaysController::class    .  "@update"          => $this->update(),
            
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
        // request()->merge(
        //     collect(json_decode(request()->getContent(),true))->transform(function ($value) {
        //         return is_string($value) ? Str::lower($value) : $value;
        //     })->all()
        // );
        
        return [
            "activity_slug" => 'required|string|exists:activities,slug',
            "activity_hours" => 'array',
            "activity_hours.*.weekday" => 'required|string|exists:week_days,slug',
            "activity_hours.*.start_time" => 'required|date_format:H:i',
            "activity_hours.*.end_time" => 'required|date_format:H:i|after:start_time',
        ];
    }
}
