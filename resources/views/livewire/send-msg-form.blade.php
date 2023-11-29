<div>


    <div class="form-control w-full max-w-full py-2">


        <form wire:submit.prevent="submitForm">

            @if ($isVisible && session()->has('message'))
            <div wire:poll.5s> 
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('message') }}
                </div>
            </div>
            @endif

            <label class="label">
                <span class="label-text">Número:</span>
            </label>

            <div class="join w-full">

                <div>
                    <input class="input input-bordered w-16 join-item" placeholder="+55" />
                </div>
                <div class=" w-full">
                    <input type="number" id="phone" wire:model="phone" placeholder="Exemplo 449987654321"
                        class="input input-bordered join-item w-full" />
                </div>
            </div>
            @error('phone') <span class="text-error">{{ $message }}</span> @enderror
            <br>
            <label class="label">
                <span class="label-text">Mensagem:</span>
            </label>
            <textarea type="text" id="msg" wire:model="msg" placeholder="Escreva a mensagem"
                class="textarea textarea-bordered w-full">
            </textarea>
            @error('msg') <span class="text-error">{{ $message }}</span> @enderror
            <br>
            <button type="submit" class="btn mt-4">Enviar</button>
        </form>




    </div>
</div>