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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('code');
            $table->string('name');
            $table->foreignId('category_id');
            $table->foreignId('type_presentation_id');
            $table->foreignId('type_administration_id');
            $table->foreignId('medicament_id');
            $table->integer('unit_per_package');
            $table->string('concentration_size');
            $table->integer('minimum_stock');
            $table->string('search')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
