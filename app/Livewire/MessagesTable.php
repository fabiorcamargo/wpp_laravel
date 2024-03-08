<?php

namespace App\Livewire;

use App\Models\WppConnect;
use App\Models\WppMessage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On; 

class MessagesTable extends Component
{
    use WithPagination;
    public $wpp;

    public function atualizarTabela()
    {
        // Lógica para atualizar a tabela aqui
        $this->wpp->load('Messages');
        // Outras lógicas de atualização, se necessário
    }

    public function render()
    {
        $this->atualizarTabela();
        
        return view('livewire.messages-table', [
            'mensagens' => $this->wpp->Messages()->orderBy('created_at', 'desc')->paginate(10, pageName: 'message')
        ]);
    }
}
