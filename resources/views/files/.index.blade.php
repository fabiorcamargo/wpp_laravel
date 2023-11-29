<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/resumable.js') }}"></script>
<x-app-layout>
    <div class="container mx-auto pt-8 ">
        <div class="card w-full bg-base-100 shadow-xl">
            {{--<figure class="pt-8 "><img class=' w-24 ' src="{{asset('Logo Vetorial.svg')}}" alt="logo" /></figure>--}}
            <div class="card-body">
                <h2 class="card-title">Gerenciamento de arquivos!</h2>
                {{--<p>If a dog chews shoes whose shoes does he choose?</p>--}}
                <div class="overflow-x-auto">
                    <table class="table">
                        <!-- head -->
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Tamanho</th>
                                <th>Status</th>

                                {{--<th>Favorite Color</th>--}}
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($files as $file)
                            <!-- rows -->
                            <input type="checkbox" id="my_modal{{ $file->name }}" class="modal-toggle" />
                            <div class="modal">
                                <div class="modal-box">
                                    @if(Str::contains($file->type, 'video'))
                                    <video id="do-video{{ $file->name }}" src="/storage/{{$file->name}}" controls preload="none"></video>
                                    
                                    @elseif(Str::contains($file->type, 'image'))
                                    <img id="imagePreview{{ $file->name }}" src="/storage/{{$file->name}}" alt="img"/>
                                    @endif
                                </div>
                                <label class="modal-backdrop" for="my_modal{{ $file->name }}" @if(Str::contains($file->type, 'video')) onclick="pauseVideo('{{ $file->name }}')" @endif>Close</label>
                            </div>
                            <tr>
                                <td>
                                    <div class="flex items-center space-x-3">
                                        <div>
                                            <div class="">{{ $file->name }}</div>

                                            {{--<div class="text-sm opacity-50">United States</div>--}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center space-x-3">
                                        <div>
                                            <div class="">{{ $file->size }}</div>

                                            {{--<div class="text-sm opacity-50">United States</div>--}}
                                        </div>
                                    </div>
                                </td>
                                {{--<td>
                                    <article class="prose">
                                        <p>
                                            But a recent study shows that the celebrated appetizer may be linked to a
                                            series of rabies cases
                                            springing up around the country.
                                        </p>
                                        <!-- ... -->
                                    </article>
                                </td>--}}
                                <td>
                                    @if($file->status == 'Pendente')
                                    <div class="badge badge-warning badge-outline">{{$file->status}}</div>
                                    @elseif($file->status == 'Concluído')
                                    <div class="badge badge-success badge-outline">{{$file->status}}</div>
                                    @endif
                                </td>
                                <th>
                                    <div class="join">
                                        <div class="join-item tooltip" data-tip="Reproduzir">
                                            <label for="my_modal{{ $file->name }}" class="btn btn-square btn-success"  @if(Str::contains($file->type, 'video')) onclick="loadVideo('{{ $file->name }}')" @endif>
                                                <x-feathericon-play-circle />
                                            </label>
                                        </div>
                                        <div class="join-item tooltip" data-tip="Download">
                                            <form action="{{ route('files.download', ['filename' => $file->name]) }}" method="POST"
                                                class="inline-block">
                                                @csrf
                                                <button type="submit" class="btn btn-square btn-info">
                                                    <x-feathericon-download-cloud  />
                                                </button>
                                            </form>
                                        </div>

                                        <div class="join-item tooltip" data-tip="Excluir">
                                            <form action="{{ route('files.delete', ['id' => $file->id, 'name' => $file->name]) }}" method="POST"
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
                                    <button id="browseFile" class="btn btn-primary">Escolher Arquivo</button>
                                </div>
                                <div style="display: none" class="progress mt-3" style="height: 25px">
                                    <progress class="progress progress-primary w-full" value="80" max="100"></progress>
                                    
                                </div>
                                <div class="text-center" >
                                <span class="badge text" style="display: none">0%</span>
                            </div>
                            </div>
            
                            {{--<div class="card-footer p-4" style="display: none">
                                <img id="imagePreview" src="" style="width: 100%; height: auto; display: none" alt="img"/>
                                <video id="videoPreview" src="" controls style="width: 100%; height: auto; display: none"></video>
                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        let browseFile = $('#browseFile');
        let resumable = new Resumable({
            target: "{{ route('upload.store') }}",
            query: {_token: '{{ csrf_token() }}'},
            fileType: ['png', 'jpg', 'jpeg', 'mp4'],
            chunkSize: 10 * 1024 * 1024, // default is 1*1024*1024, this should be less than your maximum limit in php.ini
            headers: {
                'Accept': 'application/json'
            },
            testChunks: false,
            throttleProgressCallbacks: 1,
        });
    
        resumable.assignBrowse(browseFile[0]);
    
        resumable.on('fileAdded', function (file) { // trigger when file picked
            showProgress();
            resumable.upload() // to actually start uploading.
        });
    
        resumable.on('fileProgress', function (file) { // trigger when file progress update
            updateProgress(Math.floor(file.progress() * 100));
        });
    
        resumable.on('fileSuccess', function (file, response) { // trigger when file upload complete
            response = JSON.parse(response)
    
            /*console.log(response);
            if (response.mime_type.includes("image")) {
                $('#imagePreview').attr('src', response.path + '/' + response.name).show();
            }
    
            if (response.mime_type.includes("video")) {
                $('#videoPreview').attr('src', response.path + '/' + response.name).show();
            }*/

            /*$(document).ready(function() {

                $.ajax({
                    url: '/teste/' + encodeURIComponent(response.name),
                    type: 'GET',
                    success: function(data) {
                        // Manipule a resposta da rota aqui
                        console.log('Resposta da rota:', data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro na requisição:', error);
                    }
                });
            });*/
    
            $('.card-footer').show();
        });
    
        resumable.on('fileError', function (file, response) { // trigger when there is any error
            alert('file uploading error.')
        });
    
        let progress = $('.progress');
        var progressBar = $("div.progress progress");
        var spanElement = $("span.badge.text");

    
        function showProgress() {
            progress.find('.progress-bar').html('0%');
            progress.find('.progress-bar').removeClass('bg-success');
            spanElement.show();
            progress.show();
        }
    
        function updateProgress(value) {
            spanElement.text(`${value}%`); // Substitua "50%" pelo texto desejado
            progressBar.attr("value", value); // Substitua 50 pelo valor desejado
    
            if (value === 100) {
                progressBar.addClass('progress-success');
                spanElement.text('Concluído');
                spanElement.addClass('badge-accent badge-outline');
                setTimeout(function () {
                    location.reload(); // Recarrega a página
                }, 3000); // 5000 milissegundos (5 segundos)
            }
        }
    
        function hideProgress() {
            progress.hide();
        }
    </script>

<script type="text/javascript">
    // Verifica se a mensagem 'reload' está definida
    @if(session('reload'))
        setTimeout(function () {
            location.reload(); // Recarrega a página
        }, 5000); // 5000 milissegundos (5 segundos)
    @endif
</script>

   <script>
            function loadVideo(fileName) {

                
                const videoElement = document.getElementById(`do-video${fileName}`);
                const videoSource = `{{ route('stream.video', ['video' => '']) }}/${fileName}`; // Note que incluímos um espaço vazio para o parâmetro

                videoElement.setAttribute('src', videoSource);
                videoElement.load(); // Carrega o vídeo
                videoElement.play(); // Inicia a reprodução
            }

            function pauseVideo(fileName) {
                const videoElement = document.getElementById(`do-video${fileName}`);
                videoElement.pause(); // Pausa a reprodução
            }
    </script>
</x-app-layout>