<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityWeekdayResource extends JsonResource
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
            'name' => $this->name,
            'price' => $this->price,
            'weekdays_count' => $this->weekdays_count,
            'weekdays' => $this->whenLoaded('weekdays', function () {
                return $this->weekdays->map(function ($weekday) {
                    return [
                        'slug' => $weekday->slug,
                        'name' => $weekday->name,
                    ];
                });
            }),
        ];
    }
}
