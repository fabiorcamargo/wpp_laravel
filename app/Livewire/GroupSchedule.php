<?php

namespace App\Livewire;

use App\Models\WppMessage;
use Livewire\Component;
use Livewire\WithPagination;

class GroupSchedule extends Component
{
    use WithPagination;
    public $wpp;
    public $search;
    public $isVisible = true;
    public $showModal = '';
    public $key;

    public $grupo;
    public $name;
    public $group_id;
    public $date;
    public $time;
    public $repeat;
    public $period;
    public $active;

    protected $rules = [
        'date' => ['required', 'date'],
        'time' => ['required', 'time'],
        'repeat' => ['required', 'number'],
        'period' => ['required', 'string'],
        'active' => ['required', 'string'],
    ];

    protected $messages = [
        'date.*' => 'Data é obrigatório',
        'time.*' => 'Horário é obrigatório',
        'repeat.*' => 'Recorrência é obrigatório',
        'period.*' => 'Período é obrigatório',
        'active.*' => 'Ativo é obrigatório',
    ];

    public function mount($key, $grupo)
    {
        $this->key = $key;
        $this->name = $grupo->name;
        $this->group_id = $grupo->group_id;
    }

    public function submit(){


        

        $this->validate();

        $this->agendar();
    }

    public function agendar(){
        dd(
             $this->name,
             $this->group_id,
             $this->date,
             $this->time,
             $this->repeat,
             $this->period,
             $this->active
        );
    }

    public function closeModal()
    {
        $this->dispatch('close_modal');
    }

    public function render()
    {
        return view('livewire.group-schedule');
    }
}
