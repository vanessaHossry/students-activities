<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductTranslation;
use Illuminate\Support\Facades\Log;
use App\Interfaces\ProductInterface;

class ProductRepository implements ProductInterface
{
    public function getProduct($slug){
        return Product::where("slug",$slug)->active()->first();
    }
 
    public function createTranslation($request, $product)
    {
        $t = [
            "locale" => $request->language,
            "subtitle" => $request->product_subtitle,
            "description" => $request->description,
        ];

        //   $checkTranslation = $product->whereHas('translations', function ($query) use ($request) {
        //     $query->where('locale', $request->language);
        // })->first();
        $checkTranslation = $product->translations()->where('locale', $request->language)->get();
       
        if (count($checkTranslation) != 0)
            $product->translations()->where("locale", $request->language)->update($t);
        else {
            $translation = new ProductTranslation($t);
            $product->translations()->save($translation);
        }
    }
}
