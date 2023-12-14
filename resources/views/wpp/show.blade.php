<x-app-layout>

    {{--}} @if ($errors->any())
    <div class="toast toast-center toast-middle z-50" id="toast">
        <div class="alert alert-error">
            <span>
                @foreach ($errors->all() as $error)
                <p>{{ $error }} </p>
                @endforeach
                <button class="btn btn-block  btn-xs mt-4"
                    onclick="document.getElementById('toast').style.display = 'none'">Fechar</button>
            </span>
        </div>
    </div>
    @endif--}}

    <div class="mx-auto pt-4 px-4">
        @livewire('status-session-lw', ['id' => $wpp->id])

    </div>
    


    <div class="mx-auto p-4">
        <div class="flex flex-col w-full border-opacity-50">
            @livewire('messages-table', ['wpp' => $wpp])
            <div class="divider divider-horizontal"></div>
            @livewire('groups-table', ['wpp' => $wpp])
        </div>
        {{-- <div class="container mx-auto pt-8">
            <div class="flex flex-col bg-base-100 w-full items-baseline justify-center pb-8">
                <div class="grid flex-grow card w-full m-2">
                    {{--<figure class="pt-8 "><img class=' w-24 ' src="{{asset('Logo Vetorial.svg')}}" alt="logo" />
                    </figure>

                    <div class="card-body flex justify-center items-center">
                        <h2 class="card-title">Instância {{$wpp->name}}</h2>
                        {{--<p>If a dog chews shoes whose shoes does he choose?</p>
                        <div class="overflow-x-auto">
                            <p>ID: {{$wpp->session}}</p>
                            <p>Número: {{$wpp->phone}}</p>
                        </div>
                    </div>
                    <div class="container pt-4">
                        <div class="text-center">
                            @livewire('status-session-lw', ['id' => $wpp->id])
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full border-opacity-50">
                @livewire('messages-table', ['wpp' => $wpp])
                <div class="divider divider-horizontal"></div>
                @livewire('groups-table', ['wpp' => $wpp])
            </div>
        </div> --}}


        <dialog id="my_modal_send" class="modal">
            <div class="modal-box">
                <h3 class="text-lg font-bold">Insira as informações para mensagem:</h3>

                @livewire('send-msg-form', ['session' => $wpp->session])

            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>


        <dialog id="my_modal_lote" class="modal">
            <div class="modal-box">
                <h3 class="text-lg font-bold">Insira as informações do Grupo:</h3>
                @livewire('create-group-form', ['name' => $wpp->name])
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    </div>






</x-app-layout>