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
        Schema::create('summoners', function (Blueprint $table) {
            $table->id();
            $table->string('puuid')->unique();
            $table->string('game_name');
            $table->string('tag_line');
            $table->string('summoner_id');
            $table->string('account_id')->nullable();
            $table->integer('profile_icon_id')->nullable();
            $table->integer('summoner_level')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summoners');
    }
};
