<x-app-layout>

{{--}}    @if ($errors->any())
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

    

    <div class="container mx-auto pt-8">
        <div class="flex flex-col w-full md:flex-row items-baseline   justify-center">
            <div class="grid flex-grow card w-full bg-base-100 shadow-xl m-2">
                {{--<figure class="pt-8 "><img class=' w-24 ' src="{{asset('Logo Vetorial.svg')}}" alt="logo" />
                </figure>
                --}}
                <div class="card-body flex justify-center items-center">
                    <h2 class="card-title">Instância {{$wpp->name}}</h2>
                    {{--<p>If a dog chews shoes whose shoes does he choose?</p>--}}
                    <div class="overflow-x-auto">
                        <p>Número: {{$wpp->phone}}</p>
                    </div>
                </div>
                <div class="container pt-4">
                    <div class="text-center">
                        @livewire('status-session-lw', ['id' => $wpp->id])
                    </div>
                </div>
            </div>
            @livewire('messages-table', ['wpp' => $wpp->id])
        </div>
    </div>



    <!-- The button to open modal -->


    <!-- Put this part before </body> tag -->
    <input type="checkbox" id="my_modal_7" class="modal-toggle" />
    <div class="modal">

        <div class="modal-box">
            <h3 class="text-lg font-bold">Insira as informações da instância:</h3>

            <div class="form-control w-full max-w-full pt-8">

                <form action="{{route('wpp.store')}}" method="post">
                    @csrf
                    <label class="label">
                        <span class="label-text">Nome:</span>
                    </label>
                    <input type="text" id="name" name="name" placeholder="Type here"
                        class="input input-bordered w-full max-w-full  " />

                    <label class="label">
                        <span class="label-text">Número do Whatsapp:</span>
                    </label>
                    <input type="text" id="phone" name="phone" placeholder="Type here"
                        class="input input-bordered w-full max-w-full" />

                    <button type="submit" class="btn mt-4">Criar</button>
                </form>

            </div>
        </div>
        <label class="modal-backdrop" for="my_modal_7">Close</label>
    </div>

    <!-- Put this part before </body> tag -->
<input type="checkbox" id="my_modal_send" class="modal-toggle" />
<div class="modal">

    <div class="modal-box">
        <h3 class="text-lg font-bold">Insira as informações da instância:</h3>

        @livewire('send-msg-form', ['name' => $wpp->name])
        
    </div>
    <label class="modal-backdrop" for="my_modal_send">Close</label>
</div>
</x-app-layout>