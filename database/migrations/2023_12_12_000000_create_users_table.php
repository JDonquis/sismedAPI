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
        Schema::create('users', function (Blueprint $table) {
           
            $table->id();
            $table->string('entity_code');
            $table->foreign('entity_code')
                  ->references('code')
                  ->on('hierarchy_entities')
                  ->constrained()
                  ->onDelete("restrict")
                  ->onUpdate("cascade");
            $table->string('charge',100);
            $table->string('username',20)->unique();
            $table->string('name');
            $table->string('last_name');
            $table->string('ci')->unique();
            $table->string('phone_number',13);
            $table->string('address',100);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('search',1000)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
