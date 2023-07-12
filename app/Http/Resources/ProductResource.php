<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\TranslationsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {   //Log::info("rrr ".$this);
        return [
            'slug' => $this->slug,
            'translations' => TranslationsResource::collection($this->translations)
            
        ];
    }
}
// 'translations' => $this->whenLoaded('translations', function () {
//     return $this->translations->map(function ($translation) {
//         return [
//             'locale' => $translation->locale,
//             'subtitle' => $translation->subtitle,
//             'description' => $translation->description,
//         ];
//     });
// }),