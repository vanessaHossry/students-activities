<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
class ProductTranslation extends Model
{
    use HasFactory, HasUuids;
    protected $table ="product_translations";
  //  public $translatable = ['subtitle','description'];
    protected $fillable = [
        'product_slug',
        'locale',
        'featuring_img',
        'subtitle',
        'description',
        
    ];

    protected $hidden=[
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function Product(){
        return $this->belongsTo(Product::class, 'slug', 'product_slug');
    }
}
