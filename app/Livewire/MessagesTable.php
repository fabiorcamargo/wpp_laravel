<?php

namespace App\Livewire;

use App\Models\WppConnect;
use Livewire\Component;
use Livewire\WithPagination;

class MessagesTable extends Component
{
    use WithPagination;
    public $wpp;
    public function render()
    {


        return view('livewire.messages-table', [
            'mensagens' => $this->wpp->Messages()->orderBy('created_at', 'desc')->paginate(10, pageName: 'message')
        ]);
    }
}
