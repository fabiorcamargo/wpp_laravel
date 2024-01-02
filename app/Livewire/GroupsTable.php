<?php

namespace App\Livewire;

use App\Http\Controllers\WppConnectController;
use App\Http\Controllers\WppScheduleController;
use App\Jobs\WppInstanceMessageSend;
use App\Models\WppGroup;
use App\Models\WppMessage;
use App\Models\WppSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class GroupsTable extends Component
{
    use WithPagination;
    public $wpp;
    public $search;
    public $isVisible = true;
    public $showModal = '';
    public $showList = '';
    public $showDeleteModal = '';
    public $key = false;

    public $data = [];

    public $name;
    public $group_id;
    public $wpp_group_id;
    public $grupo;
    public $date;
    public $time;
    public $repeat;
    public $period;
    public $active;
    public $body;
    public $sch;

    public $listShow = '';

    protected $rules = [
        'wpp_group_id' => ['required'],
        'date' => ['required', 'date'],
        'time' => ['required', 'string'],
        'repeat' => ['required', 'numeric'],
        'period' => ['required', 'string'],
        'active' => ['required', 'string'],
        'body' => ['required', 'string'],
    ];

    protected $messages = [
        'date.*' => 'Data é obrigatório',
        'time.*' => 'Horário é obrigatório',
        'repeat.*' => 'Recorrência é obrigatório',
        'period.*' => 'Período é obrigatório',
        'active.*' => 'Ativo é obrigatório',
        'wpp_group_id.*' => 'Grupo é obrigatório',
        'body.*' => 'Mensagem é obrigatório'
    ];

    protected $listeners = ['close_modal' => 'closeModal'];



    public function open_modal($key, $grupo)
    {

        $this->name = $grupo['name'];
        $this->group_id = $grupo['group_id'];
        $this->showModal = $key;
    }

    public function open_modal_list($key, WppGroup $grupo)
    {

        $this->name = $grupo['name'];
        $this->group_id = $grupo['group_id'];
        $this->showList = $key;
        $this->sch =  WppSchedule::where('wpp_group_id', $grupo->id)->get();
    }

    public function delete_modal(WppSchedule $sc)
    {
        $this->showDeleteModal = $sc->id;
    }
    public function call_delete(WppSchedule $sc, Request $request)
    {
        $sc->delete();

        $request->session()->flash('flash.banner', 'Agendamento excluído com sucesso!');
        $request->session()->flash('flash.bannerStyle', 'danger');

        return redirect()->route('wpp.show', ['wpp' => $this->wpp->id]);
    }
    public function close_delete_modal()
    {
        $this->showDeleteModal = '';
    }


    public function list_show($key)
    {

        $this->listShow = $key;
    }


    public function update($itemId, Request $request)
    {

        $this->sch[$itemId]->date = $this->date == "" ? $this->sch[$itemId]->date : $this->date;
        $this->sch[$itemId]->time = $this->time == "" ? $this->sch[$itemId]->time : $this->time;
        $this->sch[$itemId]->repeat = $this->repeat == "" ? $this->sch[$itemId]->repeat : $this->repeat;
        $this->sch[$itemId]->period = $this->period == "" ? $this->sch[$itemId]->period : $this->period;
        $this->sch[$itemId]->active = $this->active == "" ? $this->sch[$itemId]->active : $this->active;

        $this->sch[$itemId]->save();

        $this->listShow = '';
        $this->resetValidation();
    }


    public function closeModal()
    {

        $this->showModal = '';
        $this->name = '';
        $this->group_id = '';
        $this->date = '';
        $this->time = '';
        $this->repeat = '';
        $this->period = '';
        $this->active = '';
        $this->body = '';
        $this->resetValidation();
    }

    public function closeModalList()
    {

        $this->showList = '';
    }

    public function up_groups()
    {
        $WppControler = new WppConnectController;
        $WppControler->get_groups($this->wpp->id);
    }

    public function clearSearch()
    {
        $this->search = "";
    }

    public function submit(Request $request)
    {

        $this->wpp_group_id = WppGroup::where('group_id', $this->group_id)->first()->id;
        //dd($this->group_id);
        $this->validate();

        $this->agendar();

        $request->session()->flash('flash.banner', 'Agendamento criado com sucesso!');
        $request->session()->flash('flash.bannerStyle', 'success');

        return redirect()->route('wpp.show', ['wpp' => $this->wpp->id]);

    }

    public function agendar()
    {
        $this->time = $this->time . ":00";

        $this->wpp->Schedule()->create([
            'wpp_group_id' => WppGroup::where('group_id', $this->group_id)->first()->id,
            'name' => $this->name,
            'date' => $this->date,
            'time' => $this->time,
            'repeat' => $this->repeat,
            'period' => $this->period,
            'active' => $this->active,
            'body' => $this->body
        ]);
    }

    public function render()
    {

        $query = $this->wpp->Groups()->orderBy('creation', 'desc');

        if ($this->search) {
            $query->where(function ($subquery) {
                $subquery->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('group_id', $this->search);
            });
        }

        return view('livewire.groups-table', [
            'grupos' => $query->paginate(10, pageName: 'group'),
        ]);
    }
}
