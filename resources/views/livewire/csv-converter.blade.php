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


    <div class="flex flex-col w-full lg:flex-row p-4">
        <div class="card card-normal bg-base-100 p-6 w-full">
            <div class="py-4 text-center">
                <h2 class="  text-2xl">Coloque o modelo de mensagem que deseja enviar:</h2>
                <p>Se deseja utilizar as variáveis elas precisam ser com o mesmo nome da coluna e seu nome entre <span class="text-warning">@{{}}</span> exemplo
                    <span class="text-warning">{{Nome}}</span> </p>
            </div>
            <div class="overflow-x-auto h-96">
                <textarea class="textarea textarea-bordered textarea-md w-full h-full"
                    placeholder="Mensagem para enviar" wire:model="msg"></textarea>
            </div>
        </div>

        <div class="divider-vertical lg:divider-horizontal"></div>

        @if (!empty($data))

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
                    <button class="btn btn-primary w-full" wire:click="SaveBatch">Enviar</button>
                </div>
            </div>
        </div>


        @endif

    </div>




</div>
