<?php

namespace App\Models;

use App\Models\Activity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeekDay extends Model
{
    use HasFactory,HasSlug,HasUuids;
    protected $table='week_days';
    protected $fillable=[
        'slug',
        'name',
    ];

    protected $hidden=[
        'id',
        'created_at',
        'updated_at'
    ];

    public function activities(){
        return $this->belongsToMany(Activity::class, "activities_week")->withPivot('start_time', 'end_time')->withTimestamps();
    }

    function getSlugOptions():SlugOptions
    {
        return SlugOptions::create()
        ->generateSlugsFrom('name')
        ->saveSlugsTo('slug');
    }
}
