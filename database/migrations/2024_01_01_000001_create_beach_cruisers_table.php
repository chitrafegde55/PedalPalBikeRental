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
        Schema::create('beach_cruisers', function (Blueprint $table) {
            $table->id();
            $table->string('bike_id')->unique();
            $table->string('model_name');
            $table->string('color');
            $table->string('frame_size');
            $table->decimal('daily_rate', 8, 2);
            $table->boolean('is_available')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beach_cruisers');
    }
};