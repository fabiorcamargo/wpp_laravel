<?php

namespace App\Http\Controllers;

use App\Jobs\DeleteProcess;
use App\Jobs\UploadFileToDigitalOcean;
use App\Jobs\UploadProcess;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        $files = Files::all();
        
    return view('files.index', compact('files'));
    }

    public function upload(Request $request)
    {
        
        $file = $request->file('file');
        
        // Armazene temporariamente o arquivo em disco
        Storage::disk('temp_uploads')->put($file->getClientOriginalName(), file_get_contents($file)); // Faz o upload do arquivo para o espaço
        $tempFilePath = Storage::disk('temp_uploads')->path($file->getClientOriginalName());

        $name = $file->getClientOriginalName();

        UploadProcess::dispatch($name, $tempFilePath);

        //$request->session()->flash('flash.banner', 'Enviando... Tempo médio de upload 2min, após esse tempo atualize a página para vê-lo!');
        return redirect()->route('dashboard');
    }

    public function download(Request $request)
    {
        //dd($request->all());
        
        return Storage::disk('public')->download($request->filename);
    }

    public function delete(Request $request)
    {

		//dd($request->all());
        //dd($id, $name);
        //dd(Storage::disk('do_spaces')->exists($name)); // Exclui o arquivo do espaço
        //Storage::disk('do_spaces')->delete($filename); // Exclui o arquivo do espaço
        //dispatch(new DeleteProcess($id, $name));

        $files = Files::find($request->id);
        Storage::disk('public')->delete($files->name);
        $files->delete();
        //$request->session()->flash('reload', true);
        return redirect()->route('dashboard')->banner('Arquivo excluído com sucesso!');
    }

    public function stream(Request $request)
    {
        //dd($request->all());
        $videoPath = $request->video; // Caminho do vídeo no armazenamento
    
        $disk = Storage::disk('do_spaces'); // Use o nome do disco configurado para o DigitalOcean Space
    
        if ($disk->exists($videoPath)) {
            $stream = $disk->readStream($videoPath);
    
            // Lê todo o conteúdo do stream em uma string
            $contents = stream_get_contents($stream);
    
            fclose($stream); // Fecha o stream
    
            return new Response($contents, 200, [
                'Content-Type' => $disk->mimeType($videoPath),
            ]);
        } else {
            abort(404);
        }
    }
}
