<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class ProductCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:product-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         $products= Product::all();

        foreach ($products as $product) {
            $price = $product->price;
            switch ($price) {
                case ($price > 0 && $price < 50):
                    $discount = 5;
                    break;
                case ($price >= 50 && $price < 100):
                    $discount = 10;
                    break;
                case ($price >= 100 && $price < 500):
                    $discount = 15;
                    break;
                case ($price >= 500):
                    $discount = 20;
                    break;
            }
            $product->discount = $discount;
            $product->price = $price - $price*($discount/100);
            $product->save();
         }

    }
}
