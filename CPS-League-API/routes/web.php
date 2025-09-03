<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\searchController;
use App\Http\Controllers\SummonerController;
use Illuminate\Support\Facades\Route;


Route::get('/', [searchController::class, 'index'])->name("search");
Route::get('/summoner/{riotId}', [SummonerController::class, 'show'])->name("show");
Route::get('/login', [LoginController::class, 'index'])->name("login-page");
