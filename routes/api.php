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

Route::middleware('auth:sanctum')->group(function () {
    // Rota protegida pelo middleware de autenticação Sanctum
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/wpp/{instance}/send', function (Request $request) {
        
        return $request->user();
    });

});
