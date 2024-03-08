<div>

    <div class="mx-auto pt-4">
        <div class="card bg-base-100 shadow p-6">



            <!-- Card header -->
            <div class="items-center justify-between lg:flex">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold">Grupos</h3>
                    <span class="text-base font-normal">Lista de grupos na base de
                        dados</span>
                </div>
                <div class="items-center sm:flex">


                    <div class="flex items-center mb-4 ">
                        <div class="relative mr-4">
                            <input wire:model.live="search" type="text" placeholder="Pesquisar"
                                class="input input-bordered w-full">
                            @if($search)
                            <button wire:click="clearSearch" class="absolute top-0 right-0 mt-3 mr-4"
                                style="cursor: pointer;">X</button>
                            @endif
                        </div>
                    </div>


                    <div class="flex items-center mb-4 ">

                        <select class="select select-bordered w-full max-w-xs mr-4 ">
                            <option disabled selected>Sem Filtro</option>
                            <option>Agendados</option>
                            <option>Não Agendado</option>
                        </select>

                    </div>

                    <div class="flex items-center mb-4 ">

                        <button class="btn btn-active btn-primary" wire:click="up_groups">Atualizar</button>

                    </div>

                </div>
            </div>
            <!-- Table -->
            <div class="flex flex-col mt-6">
                <div class="overflow-x-auto rounded-lg">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden shadow sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                                            Nome
                                        </th>
                                        <th scope="col"
                                            class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                                            Id
                                        </th>

                                        <th scope="col"
                                            class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                                            Ativos
                                        </th>
                                        <th scope="col"
                                            class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                                            Encerrados
                                        </th>

                                        <th scope="col"
                                            class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                                            Agendamento
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800">
                                    <div wire:poll.15s>

                                        @foreach($grupos as $key => $grupo)
                                        <tr>
                                            <td class="px-4 py-1 text-sm font-normal whitespace-nowrap">
                                                {{$grupo->name}}
                                            </td>
                                            <td class="px-4 py-1 text-sm font-normal whitespace-nowrap">
                                                {{$grupo->group_id}}
                                            </td>


                                            <td class="px-4 py-1  whitespace-nowrap">
                                                <div class="">
                                                    <div class="join-item ms-2">
                                                        <span
                                                            class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-green-400 border border-green-100 dark:border-green-500">{{
                                                            $grupo->Schedule()->where('repeat', '>=', 1)->count()
                                                            }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-1 whitespace-nowrap">
                                                <div class="">
                                                    <div class="join-item ms-2">
                                                        <span
                                                            class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-red-400 border border-red-100 dark:border-red-500">{{
                                                            $grupo->Schedule()->where('repeat', '=', 0)->count()
                                                            }}</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-4 py-1  text-sm font-semibold whitespace-nowrap">
                                                <div class="join join-horizontal flex items-center">
                                                    <div class="tooltip tooltip-left" data-tip="Criar">
                                                        <button class="btn join-item btn-square btn-ghost btn-sm"
                                                            wire:click="open_modal({{$key}}, {{$grupo}})">
                                                            <x-heroicon-o-clock class="w-8 text-primary " />
                                                        </button>
                                                    </div>



                                                    @if($showList == $key)

                                                    <div class="tooltip tooltip-left" data-tip="Fechar">
                                                        <button class="btn join-item btn-square btn-ghost btn-sm"
                                                            wire:click="closeModalList()">
                                                            <x-heroicon-m-minus class="w-8 text-primary   " />
                                                        </button>
                                                    </div>

                                                    @else

                                                    <div class="tooltip tooltip-left" data-tip="Ver Agendamentos">
                                                        <button class="btn join-item btn-square btn-ghost btn-sm"
                                                            wire:click="open_modal_list({{$key}}, {{$grupo}})">
                                                            <x-heroicon-m-plus class="w-8 text-primary   " />
                                                        </button>
                                                    </div>

                                                    @endif

                                                </div>
                                            </td>
                                        </tr>


                                        @if($showModal == $key)
                                        <div
                                            class="fixed inset-0 bg-gray-900  bg-opacity-75 flex items-center justify-center z-50">
                                            <div class="modal-box w-11/12 max-w-5xl">
                                                <!-- Conteúdo do Modal -->
                                                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                                                    wire:click="closeModal({{$key}})">✕</button>


                                                <h3 class="text-2xl font-bold text-center">Insira as informações para
                                                    criar o
                                                    agendamento</h3>
                                                <div class="flex flex-wrap pt-8">
                                                    <!-- Três elementos dispostos horizontalmente -->

                                                    <form>
                                                        @if ($isVisible && session()->has('message'))
                                                        <div>
                                                            <div
                                                                class="alert alert-success alert-dismissible fade show">
                                                                {{ session('message') }}
                                                            </div>
                                                        </div>
                                                        @endif
                                                        <div class="w-full p-2 sm:w-1/2 md:w-1/2 lg:w-1/2 xl:w-1/2">
                                                            <div class="label">
                                                                <span class="label-text">Nome:</span>
                                                            </div>
                                                            <input type="text" wire:model.live="name" placeholder="Nome"
                                                                class="input input-bordered w-full"
                                                                value="{{ $grupo->name }}" readonly />
                                                            @error('name') <span class="text-error">{{ $message
                                                                }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="w-full p-2 sm:w-1/2 md:w-1/2 lg:w-1/2 xl:w-1/2">
                                                            <div class="label">
                                                                <span class="label-text">ID:</span>
                                                            </div>
                                                            <input type="text" placeholder="ID"
                                                                wire:model.live="group_id"
                                                                class="input input-bordered w-full"
                                                                value="{{ $grupo->group_id }}" readonly />
                                                            @error('group_id') <span class="text-error">{{ $message
                                                                }}</span>
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
                                                            @error('date') <span class="text-error">{{ $message
                                                                }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="w-full p-2 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6">
                                                            <div class="label">
                                                                <span class="label-text">Horário:</span>
                                                            </div>
                                                            <input type="time" wire:model.live="time"
                                                                class="input input-bordered w-full max-w-xs" />
                                                            @error('time') <span class="text-error">{{ $message
                                                                }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="w-full p-2 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6">
                                                            <div class="label">
                                                                <span class="label-text">Recorrência:</span>
                                                            </div>
                                                            <input type="number" wire:model.live="repeat"
                                                                placeholder="Recorrência"
                                                                class="input input-bordered w-full max-w-xs" />
                                                            @error('repeat') <span class="text-error">{{ $message
                                                                }}</span>
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
                                                                <option value=3>Semanas Alternadas</option>
                                                                <option value=4>Mensal</option>
                                                            </select>
                                                            @error('period') <span class="text-error">{{ $message
                                                                }}</span>
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
                                                            @error('active') <span class="text-error">{{ $message
                                                                }}</span>
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
                                        @endif


                                        @if($showList == $key)

                                        <div class="rounded-lg">

                                            <thead class=" bg-amber-100 dark:bg-accent-content   ">
                                                <tr>
                                                    <td class="px-4 py-1 text-sm font-normal whitespace-nowrap">Data
                                                        Início</th>
                                                    <td class="px-4 py-1 text-sm font-normal whitespace-nowrap">Horário
                                                        </th>
                                                    <td class="px-4 py-1 text-sm font-normal whitespace-nowrap">
                                                        Recorrência</th>
                                                    <td class="px-4 py-1 text-sm font-normal whitespace-nowrap">Período
                                                        </th>
                                                    <td class="px-4 py-1 text-sm font-normal whitespace-nowrap">Ativo
                                                        </th>
                                                    <td>
                                                        <div class="tooltip tooltip-left">

                                                        </div>
                                                    </td>
                                                </tr>
                                            </thead>
                                <tbody class="bg-amber-100 dark:bg-accent-content  ">

                                    @foreach($sch as $key => $sc)
                                    <tr x-data="{ isWarningActive: false }">
                                        <form wire:submit="update({{ $key }})">
                                            <td class="px-4 py-1 text-sm font-normal whitespace-nowrap">
                                                <div>
                                                    <!-- Campo de entrada inicialmente visível -->
                                                    <input type="date" value="{{$sc->date}}" x-show="!isWarningActive"
                                                        :class="{ 'input-warning': isWarningActive }"
                                                        class="input input-bordered" readonly />

                                                    <!-- Campo de entrada inicialmente oculto -->
                                                    <input type="date" wire:model="date" x-show="isWarningActive"
                                                        :class="{ 'input-warning': isWarningActive }"
                                                        class="input input-bordered" />
                                                </div>

                                            </td>
                                            <td class="px-4 py-1 text-sm font-normal whitespace-nowrap">
                                                <div>
                                                    <!-- Campo de entrada inicialmente visível -->
                                                    <input type="time" value="{{$sc->time}}" x-show="!isWarningActive"
                                                        :class="{ 'input-warning': isWarningActive }"
                                                        class="input input-bordered" readonly />

                                                    <!-- Campo de entrada inicialmente oculto -->
                                                    <input type="time" wire:model="time" x-show="isWarningActive"
                                                        :class="{ 'input-warning': isWarningActive }"
                                                        class="input input-bordered" />
                                                </div>

                                            </td>
                                            <td class="px-4 py-1 text-sm font-normal whitespace-nowrap">
                                                <div>
                                                    <!-- Campo de entrada inicialmente visível -->
                                                    <input type="number" value="{{$sc->repeat}}"
                                                        x-show="!isWarningActive"
                                                        :class="{ 'input-warning': isWarningActive }"
                                                        class="input input-bordered" readonly />

                                                    <!-- Campo de entrada inicialmente oculto -->
                                                    <input type="number" wire:model="repeat" x-show="isWarningActive"
                                                        :class="{ 'input-warning': isWarningActive }"
                                                        class="input input-bordered" />
                                                </div>

                                            </td>

                                            <td class="px-4 py-1 text-sm font-normal whitespace-nowrap">
                                                <div>
                                                    <select x-show="!isWarningActive"
                                                        :class="{ 'select-warning': isWarningActive }"
                                                        class="select select-bordered" disabled>
                                                        <option value=1 {{$sc->period == 1 ? "selected" :
                                                            ""}}>Diário
                                                        </option>
                                                        <option value=2 {{$sc->period == 2 ? "selected" :
                                                            ""}}>Semanal
                                                        </option>
                                                        <option value=3 {{$sc->period == 3 ? "selected" :
                                                            ""}}>Semanas
                                                            Alternadas</option>
                                                        <option value=4 {{$sc->period == 4 ? "selected" :
                                                            ""}}>Mensal
                                                        </option>
                                                    </select>

                                                    <select wire:model.live="period" type="number" wire:model="period"
                                                        x-show="isWarningActive"
                                                        :class="{ 'select-warning': isWarningActive }"
                                                        class="select select-bordered">
                                                        <option value=1 {{$sc->period == 1 ? "selected" :
                                                            ""}}>Diário
                                                        </option>
                                                        <option value=2 {{$sc->period == 2 ? "selected" :
                                                            ""}}>Semanal
                                                        </option>
                                                        <option value=3 {{$sc->period == 3 ? "selected" :
                                                            ""}}>Semanas
                                                            Alternadas</option>
                                                        <option value=4 {{$sc->period == 4 ? "selected" :
                                                            ""}}>Mensal
                                                        </option>
                                                    </select>
                                                </div>

                                            </td>


                                            <td class="px-4 py-1 text-sm font-normal whitespace-nowrap">
                                                <div>
                                                    <select x-show="!isWarningActive"
                                                        :class="{ 'input-warning': isWarningActive }"
                                                        class="select select-bordered" disabled>
                                                        <option value="Sim" {{$sc->active == "Sim" ? "selected" :
                                                            ""}}>Sim
                                                        </option>
                                                        <option value="Não" {{$sc->active == "Não" ? "selected" :
                                                            ""}}>Não
                                                        </option>
                                                    </select>
                                                    <select wire:model.live="active" type="number" wire:model="repeat"
                                                        x-show="isWarningActive"
                                                        :class="{ 'select-warning': isWarningActive }"
                                                        class="select select--bordered">
                                                        <option value="Sim">Sim</option>
                                                        <option value="Não">Não</option>

                                                    </select>
                                                </div>

                                            </td>
                                        </form>

                                        <td class="py-1 text-sm font-normal whitespace-nowrap">

                                            <div class="join join-horizontal flex items-start ">

                                                @if($listShow == '' || $listShow == $key)
                                                <div class="tooltip tooltip-left" data-tip="Editar">
                                                    <button class="btn join-item btn-square btn-sm btn-ghost"
                                                        wire:click="list_show({{ $key }})"
                                                        @click="isWarningActive = !isWarningActive"
                                                        x-show="!isWarningActive">

                                                        <x-heroicon-o-pencil-square class="w-6 text-success" />
                                                    </button>
                                                </div>

                                                <!-- Adiciona uma classe 'hidden' se isWarningActive for true -->
                                                <div class="tooltip tooltip-left" data-tip="atualizar">
                                                    <button class="btn join-item btn-square btn-sm btn-ghost"
                                                        wire:click="update({{ $key }})"
                                                        @click="isWarningActive = !isWarningActive"
                                                        x-show="isWarningActive ">

                                                        <x-heroicon-o-check class="w-6 text-success" />
                                                    </button>
                                                </div>

                                                <div class="tooltip tooltip-left" data-tip="Excluir">
                                                    <button class="btn join-item btn-square btn-ghost btn-sm"
                                                        wire:click="delete_modal({{$sc}})">

                                                        <x-heroicon-m-trash class="w-6 text-error" />
                                                    </button>

                                                </div>


                                                @if($showDeleteModal == $sc->id)
                                                <dialog id="my_modal_1" class="modal modal-open">
                                                    <div class="modal-box">
                                                        <h3 class="font-bold text-lg">Você deseja realmente excluir?
                                                        </h3>


                                                            <div class="pt-8">
                                                                <div class="flex-1 min-w-0">
                                                                    <span class="block text-base font-semibold text-gray-900 truncate dark:text-white">
                                                                        {{$sc->name}} | Agendamento ID: {{$sc->id}}
                                                                    </span>
                                                                    <p
                                                                        class="block text-sm font-normal truncate text-primary-700">
                                                                        Início {{date('d/m/Y', strtotime($sc->date))}} às {{$sc->time}}hs
                                                                </p>
                                                                <p
                                                                        class="block text-sm font-normal truncate text-primary-700">
                                                                        Recorrência restante:  {{$sc->repeat}}
                                                                </p>
                                                                </div>

                                                                @if($sc->active == 'Sim')
                                                                    <div class="flex items-center pt-4">
                                                                        <div class="badge badge-success badge-xs mr-2"></div> Ativo
                                                                    </div>
                                                                    @else
                                                                    <div class="flex items-center pt-4">
                                                                        <div class="badge badge-error badge-xs mr-2"></div> Desativado
                                                                    </div>
                                                                @endif
                                                                <div class="chat chat-start pt-4">
                                                                    <div class="chat-bubble whitespace-normal break-words overflow-wrap-break-word">{!! nl2br(e($sc->body)) !!}</div>
                                                                  </div>


                                                            </div>


                                                        
                                                        <div class="modal-action">
                                                                <!-- if there is a button in form, it will close the modal -->
                                                                <button class="btn btn-error"
                                                                    wire:click='call_delete({{$sc}})'>Sim</button>
                                                                    <button class="btn btn-ghost"
                                                                    wire:click='close_delete_modal'>Não</button>
                                                        </div>
                                                </dialog>
                                                @endif

                                                @endif
                                            </div>

                                        </td>
                                    </tr>



                                    @endforeach

                                </tbody>


                        </div>



                        </tr>

                        @endif
                        @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card Footer -->
        <div class="flex items-center justify-between pt-3 sm:pt-6">
            <div>
                <button
                    class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 rounded-lg hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                    type="button" data-dropdown-toggle="transactions-dropdown">Last 7 days <svg class="w-4 h-4 ml-2"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="join justify-end pt-8">
                {{ $grupos->links(data: ['scrollTo' => false]) }}
            </div>
        </div>
    </div>
</div>

</div>
