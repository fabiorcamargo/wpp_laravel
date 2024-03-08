<div>

    <div class="card md:w-96 bg-base-100 shadow">
        <div class="card-body">
            <div class="flex-1 min-w-0">
                <span class="block text-base font-semibold text-gray-900 truncate dark:text-white">
                    {{$wpp->name}}
                </span>
                <p
                    class="block text-sm font-normal truncate text-primary-700 hover:underline dark:text-primary-500">
                    {{$wpp->phone}}
            </p>
            </div>
            @if($status == 'open')
                <div class="flex items-center">
                    <div class="badge badge-success badge-xs mr-2"></div> Ativo
                </div>

                <div class="flex flex-col w-full lg:flex-row">

                    <div class="tooltip" data-tip="Parar Instância">
                    <button wire:click="StopInstance" wire:loading.remove class="btn btn-error mr-2" >
                        <x-heroicon-o-stop class="w-5"/>
                    </button>
                    </div>
                        <button class="btn btn-error mr-2" wire:loading wire:target="StopInstance">
                            <span class="loading loading-spinner"></span>
                            Iniciando
                        </button>
                    <div class="divider"></div>
                    <div class="tooltip" data-tip="Enviar Mensagem">
                    <button onclick="my_modal_send.showModal()" class="btn btn-success mr-2">
                        <x-heroicon-o-chat-bubble-bottom-center-text class="w-5" />
                    </button>
                    </div>
                    <div class="tooltip" data-tip="Envio em Lote">
                    <a href="/lote/{{$wpp->id}}/show" class="btn btn-success">
                        <x-heroicon-m-bars-arrow-up class="w-5" />
                    </a>
                    </div>
                  </div>



            @endif
            @if($status !== 'QRCODE' && $status !== 'open')
                <div class="flex items-center">
                    <div class="badge badge-error badge-xs mr-2"></div> {{$wpp->status}}
                </div>
                <div class="card-actions justify-end">
                    <button wire:click="sendRequest" wire:loading.remove class="btn btn-primary">Iniciar</button>

                    <button class="btn btn-primary" wire:loading wire:target="sendRequest">
                        <span class="loading loading-spinner"></span>
                        Iniciando
                    </button>


                </div>
            @endif

        </div>


    </div>




    {{--<h2>Status: {{ $status }}</h2>
    @if($status !== 'QRCODE' && $status !== 'CONNECTED')
    <div class="flex justify-center items-center">
        <x-heroicon-m-exclamation-circle class="text-error w-40" />
    </div>
    <button wire:click="sendRequest" wire:loading.remove class="btn btn-primary my-8">Iniciar Instância</button>
    @endif

    <div class="flex justify-center items-center pb-8" wire:loading wire:target="sendRequest">
        <button class="btn btn-primary" readonly>
            <span class="loading loading-spinner"></span>
            Processando
        </button>
    </div>


    @if($status == 'CONNECTED')

    <div class="flex justify-center items-center">
        <x-heroicon-m-check-circle class="text-success w-40" />
    </div>
    <button wire:click="StopInstance" wire:loading.remove class="btn btn-error my-8">
        <x-feathericon-stop-circle class="" />
        <h2 class="">Parar</h2>
    </button>

    <button onclick="my_modal_send.showModal()" class="btn btn-success">

        <x-feathericon-message-square class="" />
        <h2 class=" ">Mensagem</h2>
    </button>

    {{--<button onclick="my_modal_lote.showModal()" class="btn btn-secondary">
        <x-feathericon-users />
        Criar Grupo
    </button>

    @endif--}}





    @if($status == 'connecting')

        <!-- Qr Code Modal -->
        <input type="checkbox" id="my_modal_qr" class="modal-toggle" />
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="text-lg font-bold">Capture o QrCode para iniciar a Instância:</h3>
                <div class="form-control w-full max-w-full pt-8">
                    <div>
                        <div class=" text-center" >
                            <img src="{{ $qr }}" alt="QR Code">
                            {{-- <img class="inline-block" src="{{ route('qrcode', ['id' => $id]) }}" alt="QRCode"> --}}
                        </div>
                    </div>
                </div>
            </div>
            <label class="modal-backdrop" for="my_modal_qr">Close</label>
            <button wire:click="render" class="btn btn-error">Fechar</button>
        </div>

    @endif
</div>
