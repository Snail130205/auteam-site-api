<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/dictionary/getEducationType', [\App\Http\Controllers\DictionaryController::class, 'getEducationType']);

Route::post('/register/registerTeam', [\App\Http\Controllers\RegisterController::class, 'registerTeam']);

Route::get('/verified/{key}', [\App\Http\Controllers\RegisterController::class, 'verifyTeam']);
