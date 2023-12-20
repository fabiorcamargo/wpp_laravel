<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\WppConnectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Jobs\S3Upload;
use App\Mail\TestMail;
use App\Models\WppConnect;
use App\Models\WppSchedule;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/registro', function () {
    
        return view('auth.register');
    
})->name('registro');
//Route::post('/files/upload', [FileController::class, 'upload'])->name('files.upload');
//Route::post('file-upload/upload-large-files', [FileUploadController::class, 'uploadLargeFiles'])->name('files.upload.large');

//Route::get('upload', [UploadController::class, 'index'])->name('upload.index');




Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
   /* Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');*/

    Route::resource('wpp', WppConnectController::class);

    Route::get('startsession/{id}', [WppConnectController::class, 'StartSession'])->name('startsession');
    Route::get('qrcode/{id}', [WppConnectController::class, 'QrCode'])->name('qrcode');
    Route::get('status/{id}', [WppConnectController::class, 'StatusSession'])->name('status');
    Route::post('send_message', [WppConnectController::class, 'SendMessage'])->name('send_message');

    Route::get('/dashboard', [FileController::class, 'index'])->name('dashboard');
    Route::delete('/files', [FileController::class, 'delete'])->name('files.delete');
    Route::post('/files/download', [FileController::class, 'download'])->name('files.download');
    Route::get('stream-video', [VideoController::class, 'streamVideo'])->name('stream.video');
    Route::post('upload', [UploadController::class, 'store'])->name('upload.store');
    Route::get('test_email', function(){
        $now = now()->format('H:i:s');
        $now = now()->format('H:i:s');
        $nowm = now()->addMinute()->format('H:i:s');
        $day = Carbon::parse(now());

        $firstJob = WppSchedule::where('time', '>=', $now)
            ->where('time', '<', $nowm)
            ->where('repeat', '>=',  1)
            ->where('date', '<=',  $day)
            ->get();

                dd($firstJob);
    });
});
