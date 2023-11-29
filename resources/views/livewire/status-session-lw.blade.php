<div wire:poll>

    <h2>Status: {{ $status }}</h2>
    @if($status !== 'QRCODE' && $status !== 'CONNECTED')
    <button wire:click="sendRequest" wire:loading.remove class="btn btn-primary my-8">Iniciar Instância</button>
    @endif
   
    <div class="flex justify-center items-center pb-8" wire:loading wire:target="sendRequest">
        <button class="btn btn-primary" readonly>
            <span class="loading loading-spinner"></span>
            Processando
        </button>
    </div>

    @if($status == 'CONNECTED')
        <button wire:click="StopInstance" wire:loading.remove class="btn btn-error my-8">Parar Instância</button>
        <label for="my_modal_send" class="btn btn-success">Enviar Mensagem</label>
    @endif

    <div class="flex justify-center items-center pb-8" wire:loading wire:target="StopInstance">
        <button class="btn btn-error" readonly>
            <span class="loading loading-spinner"></span>
            Processando
        </button>
    </div>

    @if($status == 'QRCODE')

    <!-- Qr Code Modal -->
    <input type="checkbox" id="my_modal_qr" class="modal-toggle" />
    <div class="modal modal-open">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Capture o QrCode para iniciar a Instância:</h3>
            <div class="form-control w-full max-w-full pt-8">
                <div>
                    <div class=" text-center">
                        <img class="inline-block" src="{{ route('qrcode', ['id' => $id]) }}" alt="QRCode">
                    </div>
                </div>
            </div>
        </div>
        <label class="modal-backdrop" for="my_modal_qr">Close</label>
    </div>

    @endif
</div>



