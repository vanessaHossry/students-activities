<?php

namespace App\Models;

use App\Models\Portal;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Permission\Models\Role as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory,HasSlug;

    protected $fillable = [
        "portal_id",
        "slug",
    ];

    public function portal(){
        $this->belongsTo(Portal::class);
    }

    function getSlugOptions():SlugOptions
    {
        return SlugOptions::create()
        ->generateSlugsFrom('name')
        ->saveSlugsTo('slug')
        ->preventOverwrite();
    }

}
