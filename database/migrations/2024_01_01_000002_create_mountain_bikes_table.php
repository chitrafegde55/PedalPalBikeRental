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
        Schema::create('mountain_bikes', function (Blueprint $table) {
            $table->id();
            $table->string('bike_id')->unique();
            $table->string('model_name');
            $table->string('brand');
            $table->integer('gear_count');
            $table->string('suspension_type');
            $table->string('frame_material');
            $table->string('terrain');
            $table->decimal('weight_kg', 5, 1);
            $table->decimal('daily_rate', 8, 2);
            $table->boolean('is_available')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mountain_bikes');
    }
};