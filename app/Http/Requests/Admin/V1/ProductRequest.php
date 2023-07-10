<?php

namespace App\Http\Requests\admin\v1;

use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\admin\v1\ProductController;

class ProductRequest extends FormRequest
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
            ProductController::class    .  "@store"             => $this->store(),
            ProductController::class    .  "@updateTranslation" => $this->updateTranslation(),
        };
    }

    public function store()
    {
        return [
            "title" => 'required|string',
            "language" => ['required', 'string', Rule::in(['en', 'es', 'fr'])],
            "product_subtitle" => 'required|string',
            "description" => 'required|string',
            "price" => 'required|numeric',
            "featuring_img" => 'nullable|image|file|max:512',
            // "product_subtitle.english", "product_subtitle.frensh", "product_subtitle.espagnole" => 'required|string',
            // "description.english", "description.frensh", "description.espagnole" => 'required|string',
        ];
    }

    public function updateTranslation()
    {
        request()->merge(['product_slug' => $this->route('product_slug')]);
        return [
            "product_slug" => 'required|exists:products,slug',
            "language" => ['required', 'string', Rule::in(['en', 'es', 'fr'])],
            "product_subtitle" => 'required|string',
            "description" => 'required|string',
        ];
    }
}
