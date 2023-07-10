<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasUuids, HasSlug;
   
    protected $table='products' ;
    protected $dates='deleted_at';
   // protected $translatable = ['subtitle','description'];
    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'featuring_img',
        'description',
        'price',
        'user_id',
        'is_active',
    ];

    protected $hidden=[
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getSlugOptions():SlugOptions
    {
        return SlugOptions::create()
        ->generateSlugsFrom('title')
        ->SaveSlugsTo('slug')
        ->preventOverwrite();
    }

    public function user(){
        return $this->belongsTo(User::class);
        }

    public function translations(){
       return $this->hasMany(ProductTranslation::class,'product_slug','slug');
    }

    public function scopeActive($query){
        return $query->where('is_active',true);
    }
}
