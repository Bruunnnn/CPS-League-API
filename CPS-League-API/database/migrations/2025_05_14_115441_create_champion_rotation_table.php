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
        Schema::create('champion_rotation', function (Blueprint $table) {
            $table->id();
            $table->json('freeChampionIds')->nullable();
            $table->json('freeChampionIdsForNewPlayers')->nullable();
            $table->integer('maxNewPlayerLevel')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('champion_rotation');
    }
};
