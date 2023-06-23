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
        Schema::create('activities_week', function (Blueprint $table) {
            $table->id();
            $table->uuid('activity_id');
            $table->uuid('week_day_id');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->timestamps();

            $table->foreign('activity_id')->references('id')->on('activities');
            $table->foreign('week_day_id')->references('id')->on('week_days');
            $table->unique(['activity_id','week_day_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities_week');
    }
};
