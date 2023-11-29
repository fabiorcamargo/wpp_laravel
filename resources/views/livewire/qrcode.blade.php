<div>

    <!-- Qr Code Modal -->
    <input type="checkbox" id="my_modal{{ $id }}" class="modal-toggle" />
    <div class="modal modal-open">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Capture o QrCode para iniciar a Instância:</h3>
            <div class="form-control w-full max-w-full pt-8">
                <div>
                    @if ($showQr)
                    <div>
                        <img src="{{ $qrCodeUrl }}" alt="QRCode">
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <label class="modal-backdrop" for="my_modal{{ $id }}">Close</label>
    </div>
</div>