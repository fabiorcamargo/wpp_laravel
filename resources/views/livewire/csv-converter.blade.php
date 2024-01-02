<div>

    <div class="flex flex-col w-full lg:flex-row p-4">
        
            @if (empty($data))
            <div class="card card-normal bg-base-100 p-6">
            <form wire:submit.prevent="charge">
                <input type="file" wire:model="file" class="file-input file-input-bordered file-input-primary w-full" wire:trix-change="submitForm" />
                <button class="btn btn-primary mt-8 w-full" type="submit">Carregar</button>
            </form>
        </div>

            @else
            <div class="card card-normal bg-base-100 p-6 w-full">
                    <textarea class="textarea textarea-bordered textarea-md w-full h-full" placeholder="Mensagem para enviar" wire:model="msg"></textarea>
            </div>
            @endif


        <div class=" divider-vertical lg:divider-horizontal"></div>


        @if (!empty($data))

            <div class="card card-normal bg-base-100 p-6 w-full">
                <div class="overflow-x-auto h-96">
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




</div>