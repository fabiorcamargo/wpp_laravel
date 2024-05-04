<?php

use App\Http\Controllers\WebhookController;
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
    
    Route::post('/wpp/send_list/', [WppConnectController::class, 'SendListApi']);

    

});

// Route::post('/webhook/{session}', function(Request $request){

//     //$data = json_decode($request->getContent(), true);

//     //dd($data['mautic.form_on_submit'][0]['submission']['results']);
    

//      // Obtém os dados do corpo da requisição POST
//      //$dados = $request->all();

//      // Converte os dados para uma string formatada
//      $dadosFormatados = 'oi';
 
//      // Caminho onde o arquivo será salvo
//      $caminhoArquivo = storage_path('app/dados_formulario.txt');
 
//      // Salva os dados no arquivo de texto
//      file_put_contents($caminhoArquivo, $dadosFormatados);

// })->name('webwook.register');

Route::post('webhook/{session}/{event}', [WebhookController::class, 'register'])->name('webwook.register');
