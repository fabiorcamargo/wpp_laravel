<div>
    <div class="card md:w-96 bg-base-100 shadow">
        @if ($isVisible && session()->has('message'))
        <div class="px-4 py-2" id="notification">
            <div role="alert" class="alert alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        </div>
        @endif
        <div class="card-body">
            <div class="flex-1 min-w-0">
                <span class="block text-base font-semibold text-gray-900 truncate dark:text-white">
                    {{$wpp->name}}
                </span>
                <p class="block text-sm font-normal truncate text-primary-700 hover:underline dark:text-primary-500">
                    {{$wpp->phone}}
                </p>
            </div>
            @if($status == 'connected')

            <div class="flex items-center">
                <div class="badge badge-success badge-xs mr-2"></div> Ativo
            </div>
            <div class="flex w-full lg:flex-row">
                <div class="tooltip" data-tip="Parar Envio">
                    <button onclick="my_modal_1.showModal()" class="btn btn-error mr-2">
                        <x-heroicon-o-stop class="w-5" />
                    </button>

                    <dialog id="my_modal_1" class="modal">
                        <div class="modal-box">
                            <h3 class="text-lg font-bold">Parar Envio</h3>
                            <p class="py-4">Você realmente deseja parar o envio?<br> A fila não pode ser retomada.</p>
                            <div class="modal-action">
                                <form method="dialog">
                                    <!-- if there is a button in form, it will close the modal -->
                                    <div>
                                        <button wire:click='stopSend' class="btn btn-error px-10">Sim</button>
                                        <button class="btn">Não</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </dialog>
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
            @if($status !== 'wait_for_qrcode_auth' && $status !== 'connected')
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


    @if($status == 'wait_for_qrcode_auth')

    <!-- Qr Code Modal -->
    <input type="checkbox" id="my_modal_qr" class="modal-toggle" />
    <div class="modal modal-open">
        <div class="modal-box">
            <h3 class="text-lg font-bold text-center">Capture o QR Code para iniciar a instância:</h3>

            <div class="flex flex-col items-center justify-center pt-6">
                <img src="{{ $qr }}" alt="QR Code" class="w-40 h-40 border rounded-lg shadow-lg">
                {{-- <img class="inline-block" src="{{ route('qrcode', ['id' => $id]) }}" alt="QRCode"> --}}
                <p class="pt-2">Já capturou o QR Code? clique em atualizar.</p>
                <button onclick="location.reload()" class="btn btn mt-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h5M20 20v-5h-5M4 12a8 8 0 0116 0M20 12a8 8 0 01-16 0" />
                    </svg>
                    Atualizar
                </button>
            </div>
        </div>


        <label class="modal-backdrop" for="my_modal_qr">Close</label>
        <button wire:click="render" class="btn btn-error">Fechar</button>
    </div>

    @endif

</div>