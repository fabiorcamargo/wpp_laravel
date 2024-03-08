                                <div
                                    class="fixed inset-0 bg-gray-900  bg-opacity-75 flex items-center justify-center z-50">
                                    <div class="modal-box w-11/12 max-w-5xl">
                                        <!-- Conteúdo do Modal -->
                                        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                                            wire:click="closeModal({{$key}})">✕</button>


                                        <h3 class="text-2xl font-bold text-center">Insira as informações para criar o
                                            agendamento</h3>
                                        <div class="flex flex-wrap pt-8">
                                            <!-- Três elementos dispostos horizontalmente -->

                                            <form wire:submit='agendar'>
                                                @if ($isVisible && session()->has('message'))
                                                <div >
                                                    <div class="alert alert-success alert-dismissible fade show">
                                                        {{ session('message') }}
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="w-full p-2 sm:w-1/2 md:w-1/2 lg:w-1/2 xl:w-1/2">
                                                    <div class="label">
                                                        <span class="label-text">Nome:</span>
                                                    </div>
                                                    <input type="text" wire:model.live="name" placeholder="Nome"
                                                        class="input input-bordered w-full" value="{{ $grupo->name }}"
                                                        readonly />
                                                    @error('name') <span class="text-error">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="w-full p-2 sm:w-1/2 md:w-1/2 lg:w-1/2 xl:w-1/2">
                                                    <div class="label">
                                                        <span class="label-text">ID:</span>
                                                    </div>
                                                    <input type="text" placeholder="ID" wire:model.live="group_id"
                                                        class="input input-bordered w-full"
                                                        value="{{ $grupo->group_id }}" readonly />
                                                    @error('group_id') <span class="text-error">{{ $message }}</span>
                                                    @enderror

                                                </div>

                                                <!-- Divisão horizontal -->
                                                <div class="w-full"></div>

                                                <!-- Três elementos abaixo da divisão -->
                                                <div class="w-full p-2 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6">
                                                    <div class="label">
                                                        <span class="label-text">Início</span>
                                                    </div>
                                                    <input type="date" wire:model.live="date"
                                                        class="input input-bordered w-full max-w-xs" />
                                                    @error('date') <span class="text-error">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="w-full p-2 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6">
                                                    <div class="label">
                                                        <span class="label-text">Horário:</span>
                                                    </div>
                                                    <input type="time" wire:model.live="time"
                                                        class="input input-bordered w-full max-w-xs" />
                                                    @error('time') <span class="text-error">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="w-full p-2 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6">
                                                    <div class="label">
                                                        <span class="label-text">Recorrência:</span>
                                                    </div>
                                                    <input type="number" wire:model.live="repeat"
                                                        placeholder="Recorrência"
                                                        class="input input-bordered w-full max-w-xs" />
                                                    @error('repeat') <span class="text-error">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Divisão horizontal -->
                                                <div class="w-full"></div>

                                                <div class="w-full p-2 sm:w-1/2 md:w-1/4 lg:w-1/5 xl:w-1/6">
                                                    <div class="label">
                                                        <span class="label-text">Período:</span>
                                                    </div>
                                                    <select type="number" wire:model.live="period"
                                                        class="input input-bordered w-full">
                                                        <option selected>Selecione</option>
                                                        <option value=1>Diário</option>
                                                        <option value=2>Semanal</option>
                                                        <option>Semanas Alternadas</option>
                                                        <option>Mensal</option>
                                                    </select>
                                                    @error('period') <span class="text-error">{{ $message }}</span>
                                                    @enderror

                                                </div>


                                                <div class="w-full p-2 sm:w-1/2 md:w-1/4 lg:w-1/5 xl:w-1/6">
                                                    <div class="label">
                                                        <span class="label-text">Ativo:</span>
                                                    </div>
                                                    <select wire:model.live="active"
                                                        class="input input-bordered w-full">
                                                        <option selected>Selecione</option>
                                                        <option>Sim</option>
                                                        <option>Não</option>
                                                    </select>
                                                    @error('active') <span class="text-error">{{ $message }}</span>
                                                    @enderror

                                                </div>

                                                <div class="w-full p-2 sm:w-1/1 md:w-1/1 lg:w-1/1 xl:w-1/1">
                                                    <div class="label">
                                                        <span class="label-text">Mensagem:</span>
                                                    </div>
                                                    <textarea wire:model.live="body"
                                                        class="textarea w-full textarea-bordered"
                                                        placeholder="Mensagem"></textarea>
                                                </div>

                                                <div class="pt-2 w-full">
                                                    <button wire:click="submit"
                                                        class="btn btn-primary mt-4 w-full">Enviar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>