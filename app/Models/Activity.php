<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory,SoftDeletes,HasSlug, HasUuids;
    protected $table='activities';
    protected $dates ='deleted_at';

    protected $fillable=[
        'slug',
        'name',
        'price',
    ];

    protected $hidden=[
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function weekdays(){
        $this->belongsToMany(WeekDay::class, "activities_week");
    }

    public function getSlugOptions():SlugOptions
    {
        return SlugOptions::create()
        ->generateSlugsFrom('name')
        ->SaveSlugsTo('slug')
        ->preventOverwrite();
    }
}