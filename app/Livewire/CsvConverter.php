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

        //dd($data);
        // Armazene os dados em uma propriedade para exibição na tabela
        $this->data = $data[0];

        //dd($this->data);

        session()->flash('message', 'Arquivo CSV carregado com sucesso!');
    }

    public function SaveBatch(Request $request)
    {
        // Remove a chave especificada do array
        Arr::forget($this->data, 0);

        $this->wpp->Batch()->create([
            'msg' => $this->msg,
            'body' => json_encode($this->data),
            'status' => 0
        ]);

        $request->session()->flash('flash.banner', 'Enviados para fila de disparo!');
        $request->session()->flash('flash.bannerStyle', 'success');

        return redirect(route('lote_show', ['wpp' => $this->wpp]));
    }
}
