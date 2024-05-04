<?php

namespace App\Livewire;

use App\Http\Controllers\WppConnectController;
use Livewire\Component;

class WppIndex extends Component
{
    public $datas;
    public $response;
    public function render()
    {
        $this->datas = auth()->user()->getWpp()->get();
        $wpp = new WppConnectController;
        foreach ($this->datas as $data){
            $status = $wpp->StatusSession($data->id);
            //dd($status);
            $response[] = ['id' => $data->id, 'status' => $status];
         }

        return view('livewire.wpp-index');
    }
}
