<div>
    <img src="{{ $qrCodeUrl }}" id="qrcode" alt="QR Code">
</div>

<script>
    document.addEventListener('livewire:init', function () {
        setInterval(function () {
            @this.call('refreshQRCode');
        }, 10000); // Atualiza a cada 10 segundos
    });
</script>
