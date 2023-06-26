<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Portal extends Model
{
    use HasFactory, HasUuids, HasSlug;
    protected $table='portals';
    protected $fillable=[
        'slug',
        'name',
    ];

    protected $hidden=[
        'id',
        'created_at',
        'updated_at',
    ];

    function getSlugOptions():SlugOptions
    {
        return SlugOptions::create()
        ->generateSlugsFrom('name')
        ->saveSlugsTo('slug')
        ->preventOverwrite();
    }

    public function users(){
        return $this->hasMany(User::class);
    }
}
