<?php

use App\Http\Controllers\WppConnectController;
use App\Models\WppConnect;
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

    Route::post('/wpp/send/', [WppConnectController::class, 'SendMessageApi']);

    Route::post('/wpp/send_img/', [WppConnectController::class, 'SendImgApi']);

});
