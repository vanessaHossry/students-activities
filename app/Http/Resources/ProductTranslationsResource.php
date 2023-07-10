<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductTranslationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        //in my case it does the same thing with or without whenLoaded
        return [
            'slug' => $this->slug,
            'translations' => $this->whenLoaded('translations', function () {
                return $this->translations->map(function ($translation) {
                    return [
                        'locale' => $translation->locale,
                        'subtitle' => $translation->subtitle,
                        'description' => $translation->description,
                    ];
                });
            }),
        ];
    }
}
