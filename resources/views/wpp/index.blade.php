<x-app-layout>
    @if ($errors->any())

    <div class="toast toast-center toast-middle z-50" id="toast">
        <div class="alert alert-error">
            <span>
                @foreach ($errors->all() as $error)
                <p>{{ $error }} </p>
                @endforeach
                <button class="btn btn-block  btn-xs mt-4"
                    onclick="document.getElementById('toast').style.display = 'none'">Fechar</button>
            </span>
        </div>
    </div>
    @endif

    <div class="container mx-auto pt-8 ">

        <div class="card w-full bg-base-100 shadow-xl">
            {{--<figure class="pt-8 "><img class=' w-24 ' src="{{asset('Logo Vetorial.svg')}}" alt="logo" /></figure>
            --}}


            <div class="card-body">
                <h2 class="card-title">Gerenciamento de Instancia</h2>
                {{--<p>If a dog chews shoes whose shoes does he choose?</p>--}}
                <div class="overflow-x-auto">
                    <table class="table">
                        <!-- head -->
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Whatsapp</th>
                                <th>Status</th>

                                {{--<th>Favorite Color</th>--}}
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $data)
                            <!-- rows -->
                            <input type="checkbox" id="qr_modal{{ $data->name }}" class="modal-toggle" />

                            <tr>
                                <td>
                                    <div class="flex items-center space-x-3">
                                        <div>
                                            <a href="{{route('wpp.show', ['wpp' => $data])}}">
                                            <div class="">{{ $data->session }}</div>
                                            </a>
                                            {{--<div class="text-sm opacity-50">United States</div>--}}
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="flex items-center space-x-3">
                                        <div>
                                            <a href="{{route('wpp.show', ['wpp' => $data])}}">
                                                <div class="">{{ $data->name }}</div>
                                            </a>

                                            {{--<div class="text-sm opacity-50">United States</div>--}}
                                        </div>
                                    </div>
                                </td>

                                

                                <td>
                                    <div class="flex items-center space-x-3">
                                        <div>
                                            <div class="">{{ $data->phone }}</div>

                                            {{--<div class="text-sm opacity-50">United States</div>--}}
                                        </div>
                                    </div>
                                </td>

                                

                                <td>
                                    @if($data->status == 'CRIADO')
                                    <div class="badge badge-warning badge-outline">{{$data->status}}</div>
                                    @elseif($data->status == 'QRCODE')
                                    <div class="badge badge-success badge-outline">{{$data->status}}</div>
                                    @elseif($data->status == 'CRIANDO')
                                    <div class="badge badge-success badge-outline">{{$data->status}}</div>
                                    @elseif($data->status == 'CONNECTED')
                                    <div class="badge badge-success badge-outline">{{$data->status}}</div>
                                    @endif
                                </td>
                                <th>
                                    <div class="join">
                                        <div class="join-item tooltip" data-tip="Excluir">
                                            <form action="{{ route('wpp.destroy', ['wpp' => $data]) }}" method="POST"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-square btn-error">
                                                    <x-feathericon-x />
                                                </button>
                                            </form>
                                        </div>

                                    </div>
                                </th>
                            </tr>


                            @endforeach
                        </tbody>
                    </table>
                </div>


            </div>
            <div class="container pt-4">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header text-center">
                            </div>

                            <div class="card-body">
                                <div id="upload-container" class="text-center">
                                    <label for="my_modal_7" class="btn btn-primary">Nova Instância</label>
                                </div>
                            </div>

                            {{--<div class="card-footer p-4" style="display: none">
                                <img id="imagePreview" src="" style="width: 100%; height: auto; display: none"
                                    alt="img" />
                                <video id="videoPreview" src="" controls
                                    style="width: 100%; height: auto; display: none"></video>
                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The button to open modal -->


    <!-- Put this part before </body> tag -->
    <input type="checkbox" id="my_modal_7" class="modal-toggle" />
    <div class="modal">

        <div class="modal-box">
            <h3 class="text-lg font-bold">Insira as informações da instância:</h3>

            <div class="form-control w-full max-w-full pt-8">
                <form action="{{route('wpp.store')}}" method="post">
                    @csrf
                    <label class="label">
                        <span class="label-text">Nome:</span>
                    </label>
                    <input type="text" id="name" name="name" placeholder="Type here"
                        class="input input-bordered w-full max-w-full  " />

                    <label class="label">
                        <span class="label-text">Número do Whatsapp:</span>
                    </label>
                    <input type="text" id="phone" name="phone" placeholder="Type here"
                        class="input input-bordered w-full max-w-full" />

                    <button type="submit" class="btn mt-4">Criar</button>
                </form>

            </div>
        </div>
        <label class="modal-backdrop" for="my_modal_7">Close</label>
    </div>
</x-app-layout>