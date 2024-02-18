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
        Schema::create('outputs', function (Blueprint $table) {
            $table->id();
            $table->string('entity_code');
            $table->foreign('entity_code')
                  ->references('code')
                  ->on('hierarchy_entities')
                  ->constrained()
                  ->onDelete("restrict")
                  ->onUpdate("cascade");
            $table->integer('output_code');
            $table->foreignId('user_id');
            $table->foreignId('product_id');
            $table->foreignId('condition_id');
            $table->bigInteger('quantity');
            $table->foreignId('organization_id');
            $table->integer('guide');
            $table->string('lote_number');
            $table->string('authority_fullname');
            $table->string('authority_ci');  
            $table->string('departure_time');
            $table->string('receiver_fullname');
            $table->string('receiver_ci');
            $table->date('expiration_date');
            $table->integer('day');
            $table->integer('month');
            $table->integer('year');
            $table->string('description',200);
            $table->string('search');
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outputs');
    }
};
