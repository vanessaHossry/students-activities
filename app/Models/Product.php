<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
   
    protected $fillable = [
        'name',
        'price',
        'description',
        'quantity',
        'is_available',
    ];

    protected $hidden=[
        'id',
        'created_at',
        'updated_at',
    ];
}
