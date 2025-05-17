<?php

use App\Http\Controllers\searchController;
use App\Http\Controllers\SummonerController;
use Illuminate\Support\Facades\Route;


Route::get('/', [searchController::class, 'index'])->name("search");
Route::get('/api/summoner/{riotId}', [SummonerController::class , 'returnJson'])->name("returnJson");
Route::get('/summoner/{riotId}', [SummonerController::class, 'show'])->name("show");
Route::get('/graph', [GraphController::class,'index'])->name("graph");
Route::get('/graph',[GraphController::class,'graph'])->middleware('auth');
