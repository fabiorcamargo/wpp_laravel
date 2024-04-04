<div>

    @if (empty($data))
    <div class="min-h-screen flex items-center justify-center w-full dark:bg-gray-950">
        <div id='sendFile' class="card card-normal bg-base-100 p-6 flex items-center justify-center h-full">


            <form wire:submit.prevent="charge" class="w-full max-w-md">
                <div class="py-4 text-center">
                    <h2 class="  text-2xl">Carregue o arquivo</h2>
                    <p>As informações precisam estar conforme o <a class=" text-warning"
                            href="/Modelo_Planilha_Envio.xlsx">Modelo
                            <x-heroicon-s-document-arrow-down class="inline w-4 h-4" />
                        </a></p>
                </div>
                <div class="py-2">
                    <input type="file" wire:model="file"
                        class="file-input file-input-bordered file-input-primary w-full"
                        wire:trix-change="submitForm" />
                    <button class="btn btn-primary mt-8 w-full" type="submit">Carregar</button>
                </div>
            </form>
        </div>
    </div>


    @endif

    @if (!empty($data))

    <form wire:submit.prevent="SaveBatch">
        <div class="flex flex-col w-full lg:flex-row p-4">
            <div class="card card-normal bg-base-100 p-6 w-full">
                <div class="py-4 text-center">
                    <h2 class="  text-2xl">Coloque o modelo de mensagem que deseja enviar:</h2>
                    <p>Se deseja utilizar as variáveis elas precisam ser com o mesmo nome da coluna e seu nome entre
                        <span class="text-warning">@{{}}</span> exemplo
                        <span class="text-warning">{{Nome}}</span>
                    </p>
                </div>

                <div class="border rounded-lg border-neutral mt-8">

                    <div class="text-center p-4">
                        <label for="">Configurações</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 py-4">
                            <div class="md:col-span-1">
                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text">Tipo de Mensagem</span>
                                    </div>
                                    <select class="select select-bordered w-full" wire:model.live='batchType'>
                                        <option disabled selected>Selecione um Tipo</option>
                                        <option>Texto</option>
                                        <option>Imagem</option>
                                    </select>
                                </label>
                            </div>
                            <label class="form-control w-full max-w-xs">
                                <div class="label">
                                    <span class="label-text">Intervalo em Segundos</span>
                                </div>
                                <input type="number" id="batchDelay" wire:model.live="batchDelay"
                                    placeholder="Intervalo em Segundos" class="input input-bordered w-full " />
                            </label>
                        </div>

                    </div>
                </div>
                @if($batchType == 'Imagem')
                <div class="border rounded-lg {{ $selectedPhoto !== null ? 'border-blue-600' : 'border-red-500' }} mt-8">
                    <div class="@error('selectedPhoto')border border-spacing-3 border-error @enderror rounded-lg p-4">
                        <div class="pt-4">
                            <span class="label-text">Imagem da Mensagem</span>
                            <form class="flex flex-col sm:flex-row items-center pt-4">
                                <input type="file" wire:model="img"
                                    class="file-input file-input-bordered file-input-sm mb-2 sm:mb-0 sm:mr-2" />
                                <button class="btn btn-primary btn-sm flex-grow w-full mt-4" wire:click="saveImg">Carregar</button>
                            </form>
                        </div>


                        <div class="pt-8">
                            <span class="label-text">Selecione uma imagem:</span>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 pt-4">
                                @foreach ($photos as $photo)
                                    <div>
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
                @endif


                <div class="overflow-x-auto h-96 mt-8">
                    <textarea class="textarea textarea-bordered textarea-md w-full h-full"
                        placeholder="Mensagem para enviar" wire:model="msg" required></textarea>
                </div>
            </div>

            <div class="divider-vertical lg:divider-horizontal"></div>



            <div class="card card-normal bg-base-100 p-6 w-full">
                <div class="flex flex-col h-full">
                    <div class="overflow-x-auto flex-grow">
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
                    <div class="mt-8">
                        <button class="btn btn-primary w-full" 
                        
                        @if( $batchType == 'Imagem' && $selectedPhoto == null )
                        disabled
                        @endif
                         
                         >Enviar</button>
                    </div>
                </div>
            </div>



        </div>
    </form>
    @endif




</div>