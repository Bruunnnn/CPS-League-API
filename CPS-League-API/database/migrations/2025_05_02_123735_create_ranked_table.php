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
        Schema::create('ranked', function (Blueprint $table) {
            $table->id();
            $table->string('puuid');
            $table->string('queueType')->nullable();
            $table->string('tier')->nullable();
            $table->string('rank')->nullable();
            $table->integer('win')->nullable();
            $table->integer('losses')->nullable();
            $table->float('win_rate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranked');
    }
};
