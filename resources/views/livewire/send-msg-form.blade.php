<div>


    <div class="form-control w-full max-w-full py-2">


        <form wire:submit="submitForm">

            @if ($isVisible && session()->has('message'))
            <div>
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('message') }}
                </div>
            </div>
            @endif

            <div class="card bg-base-100 rounded-box flex-grow p-4 w-full md:w-1/2">
                <div class="@error('selectedPhoto')border border-spacing-3 border-error @enderror mt-4 rounded-lg">
                    <div class="pt-4">
                        <span class="label-text">Imagem da Header</span>
                        <div class="flex items-center gap-2">
                            <input type="file" wire:model="img" class="file-input file-input-bordered file-input-sm" />
                            <div class="tooltip" data-tip="Carregar">
                            <a class="btn btn-primary btn-square btn-sm" wire:click='saveImg'>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 12l8-8m0 0l8 8m-8-8v16" />
                                </svg>
                            </a>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <span class="label-text">Selecione uma imagem:</span>
                        <div class="grid grid-cols-2 md:grid-cols-2 gap-4 pt-4">
                            @foreach ($photos as $photo)
                            <div class="relative">
                                <a class="absolute top-2 right-2 bg-red-500 text-white rounded-full hover:bg-red-700"
                                    wire:click="deleteImg('{{ $photo->id }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                                <img src="{{ $photo->url }}" alt="Photo" @if($selectedPhoto===$photo->url)
                                class="border-4 border-blue-300 h-auto max-w-full rounded-lg"
                                @else
                                class="h-auto max-w-full rounded-lg"
                                @endif
                                wire:click="selectPhoto('{{ $photo->url }}')"
                                >
                            </div>
                            @endforeach
                        </div>

                        <!-- Botão para limpar a seleção -->
                        @if($selectedPhoto)
                        <div class="pt-4">
                            <a class="btn btn-xs" wire:click="clearSelection">
                                Limpar Seleção
                            </a>
                        </div>
                        @endif
                    </div>

                </div>
            </div>

            <label class="label">
                <span class="label-text">Número:</span>
            </label>

            <div class="join w-full">

                <div>
                    <select class="select select-bordered w-20 join-item" placeholder="+55">
                        <option selected>+55</option>
                    </select>
                </div>
                <div class=" w-full">
                    <input type="number" id="phone" wire:model.live="phone" placeholder="Exemplo 449987654321"
                        class="input input-bordered join-item w-full" autofocus />
                </div>
            </div>
            @error('phone') <span class="text-error">{{ $message }}</span> @enderror
            <br>
            <label class="label">
                <span class="label-text">Mensagem:</span>
            </label>
            <textarea type="text" id="msg" wire:model.live="msg" placeholder="Escreva a mensagem"
                class="textarea textarea-bordered w-full">
            </textarea>
            @error('msg') <span class="text-error">{{ $message }}</span> @enderror
            <br>
            <button type="submit" class="btn mt-4">Enviar</button>
        </form>




    </div>
</div>