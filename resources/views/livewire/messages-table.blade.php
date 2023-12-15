<div class="card bg-base-100 shadow">
    {{--<figure class="pt-8 "><img class=' w-24 ' src="{{asset('Logo Vetorial.svg')}}" alt="logo" />
    </figure>
    --}}
    <div class="card-body flex justify-items-start ">
        <h2 class="card-title">Mensagens Envidas</h2>
        {{--<p>If a dog chews shoes whose shoes does he choose?</p>--}}

        <div class="overflow-x-auto">
            <table class="table table-xs">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Número</th>
                        <th>Mensagem</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <div wire:poll> 
                    @foreach($mensagens as $mensagem)
                    <tr>
                        <td>{{ $mensagem->created_at->format('d/m/y H:i:s') }}</td>
                        <td>{{ $mensagem->phone }}</td>
                        @if($mensagem->type == "chat")
                        <td>{{ $mensagem->body }}</td>
                        @elseif($mensagem->type == "img")
                        <td>Imagem</td>
                        @elseif($mensagem->type == "list")
                        <td>Lista</td>
                        @endif
                        @if ($mensagem->status == 'ENVIADO')
                        <td class="badge badge-success badge-sm gap-2">{{ $mensagem->status }}</td>
                        @elseif ($mensagem->status == 'ERRO')
                        <td class="badge badge-error badge-sm gap-2">{{ $mensagem->status }}</td>
                        @endif
                    </tr>
                    @endforeach
                    </div>
                </tbody>
            </table>
        </div>


        <div class="join justify-end pt-8">

       {{ $mensagens->links(data: ['scrollTo' => false]) }}
        </div>
    </div>

</div>