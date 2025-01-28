<div>

    <div class="flex flex-col w-full lg:flex-row p-4">

        @if (empty($data))
        <div class="card card-normal bg-base-100 p-6">
            <form wire:submit.prevent="charge">
                <input type="file" wire:model="file" class="file-input file-input-bordered file-input-primary w-full"
                    wire:trix-change="submitForm" />
                <button class="btn btn-primary mt-8 w-full" type="submit">Carregar</button>
            </form>
        </div>

        @else

        <div class="card card-normal bg-base-100 p-6 w-full">
            <label class="form-control w-full max-w-xs py-4">
                Intervalo em segundos
                <input type="number" min="5" placeholder="Type here" class="input input-bordered w-full max-w-xs"
                    wire:model='delay' />
            </label>
            <textarea id="messageTextarea" class="textarea textarea-bordered textarea-md w-full h-full"
                placeholder="Mensagem para enviar" wire:model="msg"></textarea>
        </div>
        @endif


        <div class=" divider-vertical lg:divider-horizontal"></div>


        @if (!empty($data))

        <div class="card card-normal bg-base-100 p-6 w-full">
            <div class="overflow-x-auto h-96">
                <div class="pb-8">
                    <p>Clique nas opções abaixo para usar a variável</p>
                    <div class="pt-4">
                    @foreach ($data[0] as $item)
                    <button class="btn btn-warning btn-xs" onclick="insertIntoTextarea('{{ '{' . $item . '}' }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 5v14m-7-7h14" />
                        </svg>

                        {{ $item }}
                    </button>
                    @endforeach
                </div>
                </div>
                <table class="table table-pin-rows">
                    <thead>
                        <tr>
                            <th>#</th>
                            @foreach ($data[0] as $header)
                            <th>{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (array_slice($data, 1) as $key => $row)
                        <tr>
                            <th>{{$key + 1}}</th>
                            @foreach ($row as $value)
                            <td>{{ $value }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <button class="btn btn-primary mt-8" wire:click="SaveBatch">Enviar</button>

        </div>

        @endif

    </div>

    <div class="card card-normal bg-base-100 p-6 w-full">
        <progress class="progress progress-info w-56" value="0" max="100"></progress>
    </div>

</div>

<script>
    function insertIntoTextarea(text) {
        const textarea = document.getElementById('messageTextarea');
        if (textarea) {
            // Insere o texto na posição do cursor
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const currentValue = textarea.value;

            // Atualiza o valor do textarea
            textarea.value = currentValue.substring(0, start) + text + currentValue.substring(end);

            // Move o cursor para o fim do texto inserido
            textarea.selectionStart = textarea.selectionEnd = start + text.length;

            // Para Livewire, dispara um evento para sincronizar
            textarea.dispatchEvent(new Event('input'));
        }
    }
</script>

</div>