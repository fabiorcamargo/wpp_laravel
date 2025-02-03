<?php

namespace App\Livewire;

use App\Models\WppImage;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class CsvConverter extends Component
{
    use WithFileUploads;

    public $file;
    public $data = [];
    public $msg;
    public $wpp;
    public $delay = 5;
    public $dataHeader;
    public $img;
    public $imgUrl;
    public $photos;
    public $selectedPhoto;
    public $type = 'text';
    public $header;

    public $textInputs = [];
    public $imageInputs = [];

    public function gallery()
    {
        $this->photos = $this->wpp->getImage()->get();
        //dd('s');
    }

    public function selectPhoto($photo)
    {

        $this->selectedPhoto = $photo;
    }

    public function saveImg()
    {

        $this->validate(['img' => 'required|mimes:jpg,png']);

        $path = $this->img->storeAs('uploads', uuid_create() . '.' . $this->img->getClientOriginalExtension());

        $imageUrl = asset("storage/{$path}");

        $this->wpp->getImage()->create([
            'url' => $imageUrl
        ]);

        $this->imgUrl = $imageUrl;

        $this->gallery();
    }

    public function deleteImg($id)
    {
        $img = WppImage::find($id);
        if ($img) {
            $filePath = parse_url($img->url, PHP_URL_PATH); // Pega o caminho relativo
            $filePath = ltrim($filePath, '/'); // Remove a barra inicial se existir
    
            if (file_exists($filePath)) {
                unlink($filePath); // Remove o arquivo
            }
            $img->delete(); // Remove do banco
            return response()->json(['message' => 'Imagem deletada com sucesso']);
        }
    

     
    }

    public function render()
    {
        $this->gallery();
        return view('livewire.csv-converter');
    }

    public function charge()
    {

        $this->validate(['file' => 'required|mimes:csv,xlsx']);
        $path = $this->file->storeAs('uploads', 'uploaded_file.' . $this->file->getClientOriginalExtension());

        // Utilize o Laravel Excel para importar os dados do CSV
        $data = Excel::toArray(null, $path);

        // Armazene os dados em uma propriedade para exibição na tabela
        $this->data = $data[0];

        session()->flash('message', 'Arquivo CSV carregado com sucesso!');
    }

    public function SaveBatch(Request $request)
    {
        // Remove a chave especificada do array
        $this->wpp->Batch()->create([
            'type' => $this->type,
            'img' => $this->selectedPhoto,
            'msg' => '{"img": "'.$this->selectedPhoto.'", "text": "'.$this->msg.'"}',
            'body' => json_encode($this->data),
            'status' => 0,
            'delay' => $this->delay
        ]);

        $request->session()->flash('flash.banner', 'Enviados para fila de disparo!');
        $request->session()->flash('flash.bannerStyle', 'success');

        return redirect(route('lote_show', ['wpp' => $this->wpp]));
    }
}
