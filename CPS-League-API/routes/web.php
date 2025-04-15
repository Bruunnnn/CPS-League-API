<?php

use App\Http\Controllers\SummonerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\frontpageController;
use App\Http\Controllers\nikolaisController;
use App\Http\Controllers\jakobsController;
use App\Http\Controllers\petersController;

Route::get('/', [frontpageController::class, 'index']);

Route::get('/nikolais', [nikolaisController::class, 'index']);
Route::get('/jakobs', [jakobsController::class, 'index']);
Route::get("/peters", [petersController::class,'index']);

Route::get('/summoner/{name}', [SummonerController::class, 'show']);



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
