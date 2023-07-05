<?php

namespace App\Http\Requests\client\v1;


use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\client\v1\ActivitiesWeekdaysController;

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
        return match($route_action)
        {
            ActivitiesWeekdaysController::class    .  "@show"           => $this->show(),
            
        };
    }

    public function show(){
    request()->merge([
    'price' => $this->query('price'),
    'weekday' => $this->query('weekday'),
    'per_page' => $this->query('per_page')]);
    return [
        
        'weekday' => 'nullable|string|exists:week_days,slug',
        'min_price'   => 'nullable|integer',
        'max_price'   => 'nullable|integer',
        'per_page'   => 'required|integer|max:100',
    ];
    }
}
