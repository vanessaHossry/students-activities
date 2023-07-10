<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_translations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('product_slug');
            $table->enum('locale', ['en','fr','es']);
            $table->string('subtitle');
            $table->string('description');
            
            $table->timestamps();

            $table->foreign('product_slug')->references('slug')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_translations');
    }
};
