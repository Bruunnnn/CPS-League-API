<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new  class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ranked_history', function (Blueprint $table) {
            $table->id();
            $table->string('puuid')->nullable();
            $table->string('rank')->nullable();
            $table->string('wins')->nullable();
            $table->string('losses')->nullable();
            $table->float('win_rate')->nullable();
            $table->string('queue_type')->nullable();
            $table->timestamps();

            $table->foreign('puuid')->references('puuid')->on('summoners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranked_histories');
    }
};
