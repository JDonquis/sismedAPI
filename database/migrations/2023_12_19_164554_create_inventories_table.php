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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('entity_code');
            $table->foreign('entity_code')
                  ->references('code')
                  ->on('hierarchy_entities')
                  ->constrained()
                  ->onDelete("restrict")
                  ->onUpdate("cascade");
            $table->foreignId('product_id');
            $table->string('lote_number');
            $table->date('expiration_date');
            $table->foreignId('condition_id');
            $table->integer('stock')->default(0);
            $table->bigInteger('entries')->default(0)->nullable();
            $table->bigInteger('outputs')->default(0)->nullable();
            $table->string('search',1000)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
