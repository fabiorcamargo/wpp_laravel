<div class="grid flex-grow card w-full bg-base-100 shadow-xl m-2">
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

                    </tr>
                </thead>
                <tbody>
                    <div wire:poll> 
                    @foreach($mensagens as $mensagem)
                    <tr>
                        <th>{{ $mensagem->created_at->format('d/m/y H:i:s') }}</th>
                        <td>{{ $mensagem->phone }}</td>
                        <td>{{ $mensagem->body }}</td>
                    </tr>
                    @endforeach
                    </div>
                </tbody>
            </table>
        </div>


        <div class="join justify-end">

            @php $curp = $mensagens->links()->paginator->currentPage() @endphp
            @foreach($mensagens->links()->elements[0] as $key => $element)

            <a href="{{$element}}" class="join-item btn {{$curp == $key ? 'btn-primary' : ''}}">{{$key}}</a>

            @endforeach
        </div>
    </div>

</div>