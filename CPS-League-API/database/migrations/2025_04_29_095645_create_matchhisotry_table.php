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
        Schema::create('matchhisotry', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('puuid')->unique();
            $table->integer('mapId');
            $table->integer('endGameTimestamp');
            $table->boolean('win');
            $table->integer('gameDuraction');
            $table->integer('championId');
            $table->integer('kills');
            $table->integer('deaths');
            $table->integer('assists');
            $table->integer('totalMinionsKilled');
            $table->integer('enemyJungleMonsterkills');
            $table->integer('item0');
            $table->integer('item1');
            $table->integer('item2');
            $table->integer('item3');
            $table->integer('item4');
            $table->integer('item5');
            $table->integer('item6');
            $table->integer('summoner1Id');
            $table->integer('summoner2Id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matchhisotry');
    }
};
