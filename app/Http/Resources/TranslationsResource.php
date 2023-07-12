<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductTranslationsResource;

class TranslationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
            return [
                'locale' => $this->locale,
                'subtitle' => $this->subtitle,
                'description' => $this->description,
            ];
     
    }
}
