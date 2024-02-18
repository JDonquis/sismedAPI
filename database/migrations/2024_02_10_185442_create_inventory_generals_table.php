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
        Schema::create('inventory_generals', function (Blueprint $table) {
            $table->id();
            $table->string('entity_code');
            $table->foreign('entity_code')
                  ->references('code')
                  ->on('hierarchy_entities')
                  ->constrained()
                  ->onDelete("restrict")
                  ->onUpdate("cascade");
            $table->foreignId('product_id');
            $table->integer('stock_expired')->default(0);
            $table->integer('stock_bad')->default(0);
            $table->integer('stock')->default(0);
            $table->bigInteger('entries')->default(0)->nullable();
            $table->bigInteger('outputs')->default(0)->nullable(); 
            $table->string('search',1000)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_generals');
    }
};
