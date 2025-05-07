<?php

use App\Http\Controllers\searchController;
use App\Http\Controllers\SummonerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontpageController;
use App\Http\Controllers\nikolaisController;
use App\Http\Controllers\jakobsController;
use App\Http\Controllers\petersController;

Route::get('/summoner', [FrontpageController::class, 'index'])->name("summoner");
Route::get('/nikolais', [nikolaisController::class, 'index'])->name("nikolais");
Route::get('/jakobs', [jakobsController::class, 'index'])->name("jakobs");
Route::get("/peters", [petersController::class,'index'])->name("peters");
Route::get('/', [searchController::class, 'index'])->name("search");
Route::get('/api/summoner/{riotId}', [SummonerController::class , 'returnJson'])->name("returnJson");
Route::get('/summoner/{riotId}', [SummonerController::class, 'show'])->name("show");



/*
Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('frontpage');
})->name('home');

Route::get('/jakobs', function () {
    return view('jakobs');
})->name('jakobs');

Route::get('/peters', function () {
    return view('peters');
})->name('peters');

Route::get('/nikolais', function () {
    return view('nikolais');
})-> name('nikolais');
*/


// Routing for a controller (use if needed)
//Route::get('/index', [\App\Http\Controllers\AlbumController::class, 'index'])
  //  -> name('index');
