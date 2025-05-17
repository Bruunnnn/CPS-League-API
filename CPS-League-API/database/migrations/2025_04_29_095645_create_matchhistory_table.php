<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('matchHistory', function (Blueprint $table) {
            $table->id();
            $table->biginteger('gameId');
            $table->string('puuid');
            $table->integer('mapId');
            $table->integer('queueId')->nullable();
            $table->biginteger('endGameTimestamp');
            $table->boolean('win');
            $table->biginteger('gameDuration');
            $table->string('riotIdGameName');
            $table->string('riotIdTagline');
            $table->integer('championId');
            $table->integer('kills')->nullable();
            $table->integer('deaths')->nullable();
            $table->integer('assists')->nullable();
            $table->integer('totalMinionsKilled')->nullable();
            $table->integer('totalEnemyJungleMinionsKilled')->nullable();
            $table->integer('item0')->nullable();
            $table->integer('item1')->nullable();
            $table->integer('item2')->nullable();
            $table->integer('item3')->nullable();
            $table->integer('item4')->nullable();
            $table->integer('item5')->nullable();
            $table->integer('item6')->nullable();
            $table->integer('summoner1Id');
            $table->integer('summoner2Id');
            $table->integer('profileIcon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matchHistory');
    }
};
