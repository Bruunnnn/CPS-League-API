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
            $table->string('gameName')->nullable();;
            $table->string('tagLine')->nullable();;
            $table->integer('profileIconId')->nullable();
            $table->integer('summonerLevel')->nullable();
            $table->biginteger('revisionDate')->nullable();
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
