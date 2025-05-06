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
            $table->string('puuid');
            $table->string('rank');
            $table->string('wins');
            $table->string('losses');
            $table->float('win_rate');
            $table->string('queue_type'); // 'RANKED_SOLO_5x5' or 'RANKED_FLEX_SR'
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
