<div>
    <div class="flex flex-wrap p-4 gap-4">
        @if (empty($data))
            <div class="card bg-base-100 rounded-box flex-grow px-4 py-8 w-full max-w-md">
                <form wire:submit.prevent="charge">
                    <input type="file" wire:model="file" class="file-input file-input-bordered file-input-primary w-full"
                        wire:trix-change="submitForm" />
                    <button class="btn btn-primary mt-8 w-full" type="submit">Carregar</button>
                </form>
            </div>
        @else
            <div class="flex w-full gap-4">
                <div class="card bg-base-100 rounded-box flex-grow p-4 w-full md:w-1/2">
                    <label class="form-control w-full max-w-xs py-4">
                        Intervalo em segundos
                        <input type="number" min="5" placeholder="Type here" class="input input-bordered w-full max-w-xs"
                            wire:model='delay' />
                    </label>
                    
                    <textarea id="messageTextarea" class="mt-4 textarea textarea-bordered textarea-md w-full h-full"
                        placeholder="Mensagem para enviar" wire:model="msg"></textarea>
                </div>
                <div class="card bg-base-100 rounded-box flex-grow p-4 w-full md:w-1/2">
                    <div class="@error('selectedPhoto')border border-spacing-3 border-error @enderror mt-4 rounded-lg">
                        <div class="pt-4">
                            <span class="label-text">Imagem da Header</span>
                            <form wire:submit.prevent="saveImg" class="flex flex-col sm:flex-row items-center pt-4">
                                <input type="file" wire:model="img"
                                    class="file-input file-input-bordered file-input-sm mb-2 sm:mb-0 sm:mr-2" required />
                                <button class="btn btn-primary btn-sm flex-grow" type="submit">Carregar</button>
                            </form>
                        </div>
                        <div class="pt-4">
                            <span class="label-text">Selecione uma imagem:</span>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 pt-4">
                                @foreach ($photos as $photo)
                                <div class="relative">
                                    <button
                                        class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-700"
                                        wire:click="deleteImg('{{ $photo->id }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    <img src="{{ $photo->url }}" alt="Photo" @if($selectedPhoto===$photo->url)
                                    class="border-2 border-blue-600 h-auto max-w-full rounded-lg"
                                    @else
                                    class="h-auto max-w-full rounded-lg"
                                    @endif
                                    wire:click="selectPhoto('{{ $photo->url }}')"
                                    >
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
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