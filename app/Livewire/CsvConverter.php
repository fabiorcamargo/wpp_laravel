<?php

namespace App\Livewire;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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

    public $batchType = 'Texto';
    public $batchDelay = 5;
    
    public $img;
    public $imgUrl;
    public $photos;

    public $var = [];
    public $body = [];

    public $textInputs = [];
    public $imageInputs = [];

    public $selectedPhoto = null;


    public function changeType() {
        dd($this->batchType);
    }

    public function gallery()
    {
        $this->photos = $this->wpp->getImage()->get();
    }

    public function selectPhoto($photo)
    {
        $this->selectedPhoto = $photo;
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
        $data = Excel::toArray(null, ('storage/' . $path));

        // Armazene os dados em uma propriedade para exibição na tabela
        $this->data = $data[0];

        session()->flash('message', 'Arquivo CSV carregado com sucesso!');


    }

    public function SaveBatch(Request $request)
    {
        $dados = [];


        foreach ($this->data as $linha) {
            $linha_dados = [];
            foreach ($linha as $key => $value) {
                $nome_coluna = $this->data[0][$key];
                $linha_dados[$nome_coluna] = $value;
                $linha_dados['img'] = $this->selectedPhoto;
                $linha_dados['type'] = $this->batchType;
                $linha_dados['delay'] = $this->batchDelay;
            }
            $dados[] = $linha_dados;
        }

        Arr::forget($dados, 0);

        foreach ($dados as $key => $value) {
            if ($value["Telefone"] === null) {
                unset($dados[$key]);
            }
        }

        //dd($dados);

        $this->wpp->Batch()->create([
            'msg' => $this->msg,
            'body' => json_encode($dados),
            'status' => 0
        ]);

        $request->session()->flash('flash.banner', 'Enviados para fila de disparo!');
        $request->session()->flash('flash.bannerStyle', 'success');

        return redirect(route('wpp.show', ['wpp' => $this->wpp]));
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

}
