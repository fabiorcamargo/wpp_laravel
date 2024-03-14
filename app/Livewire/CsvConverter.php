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
    public $var = [];
    public $body = [];


    public function render()
    {
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
            }
            $dados[] = $linha_dados;
        }

        Arr::forget($dados, 0);

        foreach ($dados as $key => $value) {
            if ($value["Telefone"] === null) {
                unset($dados[$key]);
            }
        }

        dd($dados);



        $this->wpp->Batch()->create([
            'msg' => $this->msg,
            'body' => json_encode($dados),
            'status' => 0
        ]);

        $request->session()->flash('flash.banner', 'Enviados para fila de disparo!');
        $request->session()->flash('flash.bannerStyle', 'success');

        return redirect(route('wpp.show', ['wpp' => $this->wpp]));
    }
}
