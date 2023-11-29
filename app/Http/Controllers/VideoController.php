<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function streamVideo(Request $request)
    {
        $filePath = $request->video; // Caminho para o vídeo no Space
        $fileSize = Storage::disk('public')->size($filePath);
        $chunkSize = 1024 * 1024; // Tamanho do chunk (1 MB neste exemplo)

        // Defina os cabeçalhos de resposta
        $headers = [
            'Content-Type' => 'video/mp4', // Defina o tipo de conteúdo correto
            'Content-Length' => $fileSize,
            'Accept-Ranges' => 'bytes',
        ];

        // Verifique se o cliente suporta intervalos de bytes (para streaming)
        if (request()->hasHeader('range')) {
            $range = request()->header('range');
            $range = Str::replaceFirst('bytes=', '', $range);
            $range = explode('-', $range);

            $start = (int) $range[0];
            $end = min($start + $chunkSize, $fileSize - 1);

            $headers['Content-Range'] = "bytes $start-$end/$fileSize";
            $headers['Content-Length'] = $end - $start + 1;

            return response()->stream(function () use ($filePath, $start, $end) {
                $handle = Storage::disk('public')->readStream($filePath);
                fseek($handle, $start);

                while (!feof($handle) && ftell($handle) <= $end) {
                    echo fread($handle, 1024);
                    ob_flush();
                    flush();
                }

                fclose($handle);
            }, 206, $headers);
        } else {
            // Se o cliente não suporta intervalos de bytes, transmita o arquivo inteiro
            return Storage::disk('public')->response($filePath, null, $headers);
        }
    }
}
