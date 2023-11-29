<?php

namespace App\Http\Controllers;

use App\Jobs\S3Upload;
use App\Jobs\UploadProcess;
use App\Models\Files;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class FileUploadController extends Controller {

    /**
     * @return Application|Factory|View
     */
   

     public function uploadChunk(Request $request)
     {
         $resumableFile = $request->file('file');
         $resumableChunkNumber = $request->input('resumableChunkNumber');
         $resumableTotalChunks = $request->input('resumableTotalChunks');
         $resumableIdentifier = $request->input('resumableIdentifier');
 
         $chunkPath = storage_path('/chunks' . $resumableIdentifier);
 
         // Certifique-se de que o diretório de chunks exista
         if (!file_exists($chunkPath)) {
             mkdir($chunkPath, 0755, true);
         }
 
         // Salve o chunk no diretório
         $resumableFile->move($chunkPath, 'part_' . $resumableChunkNumber);
 
         // Verifique se todos os chunks foram recebidos
         if ($resumableChunkNumber == $resumableTotalChunks) {
             // Todos os chunks foram enviados, agora você pode recriar o arquivo original
             $finalFilePath = storage_path('app/uploads/' . $resumableIdentifier);
             $chunks = glob($chunkPath . '/part_*');
             natsort($chunks);
 
             $finalFile = fopen($finalFilePath, 'w');
 
             foreach ($chunks as $chunk) {
                 $chunkContent = file_get_contents($chunk);
                 fwrite($finalFile, $chunkContent);
                 unlink($chunk); // Remova o chunk após escrevê-lo no arquivo final
             }
 
             fclose($finalFile);
 
             // Limpe o diretório de chunks
             rmdir($chunkPath);
 
             return response()->json(['success' => true]);
         }
 
         return response()->json(['success' => true]);
     }
     

    public function uploadLargeFiles(Request $request) {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (!$receiver->isUploaded()) {
            // file not uploaded
        }

        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            $fileName = str_replace('.'.$extension, '', $file->getClientOriginalName()); //file name without extenstion
            $fileName = str_replace(' ', '_', $fileName); //Remove espaço vazio
            $fileName .= '_' . md5(time()) . '_' . '.' . $extension; // a unique file name

            Storage::disk('temp_uploads')->put($fileName, file_get_contents($file)); // Faz o upload do arquivo para o espaço

            Files::create(['name' => $fileName, 'status' => 'Pendente']);

            //dd($name);
            S3Upload::dispatch($fileName)->timeout(3600);
            //UploadProcess::dispatch($name, $tempFilePath, $fileid);

            unlink($file->getPathname());
            
            $request->session()->flash('flash.banner', 'Arquivo enviado para fila de carregamento, atualize a página para acompanhar o status!');
            $request->session()->flash('flash.bannerStyle', 'success');

        }

        // otherwise return percentage informatoin
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
    }
}
