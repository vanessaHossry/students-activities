<?php

namespace App\Interfaces;

interface ProductInterface
{
    public function getProduct($slug);
    public function storeProduct($request);
    public function createTranslation($request,$product);

}
