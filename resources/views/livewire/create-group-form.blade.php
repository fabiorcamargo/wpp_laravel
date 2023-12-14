<div>


    <div class="form-control w-full max-w-full py-2">


        <form wire:submit="submitForm">

            @if ($isVisible && session()->has('message'))
            <div wire:poll.5s> 
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('message') }}
                </div>
            </div>
            @endif

            <label class="label">
                <span class="label-text">Nome:</span>
            </label>
                <div class=" w-full">
                    <input type="text" id="phone" wire:model.live="phone" placeholder="Nome do Grupo"
                        class="input input-bordered join-item w-full" />
                </div>
            @error('phone') <span class="text-error">{{ $message }}</span> @enderror
            <br>
            <label class="label">
                <span class="label-text">Telefones:</span>
            </label>
            <textarea type="text" id="phones" wire:model.live="phones" placeholder="Digite os números separados por virgula"
                class="textarea textarea-bordered w-full">
            </textarea>
            @error('msg') <span class="text-error">{{ $message }}</span> @enderror
            <br>
            <button type="submit" class="btn mt-4">Enviar</button>
        </form>




    </div>
</div>