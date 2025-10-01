<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\searchController;
use App\Http\Controllers\SummonerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeaderboardController;



Route::get('/', [searchController::class, 'index'])->name("search");
Route::get('/summoner/{riotId}', [SummonerController::class, 'show'])->name("show");
Route::get('/login', [LoginController::class, 'index'])->name("login-page");
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name("leaderboard");

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


