<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Enviar Arquivo
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Enviar um novo arquivo:</h3>
                    <form wire:submit.prevent="uploadFile" enctype="multipart/form-data">
                        <div class="mb-4">
                            <input type="file" wire:model="file" class="py-2 px-3 border border-gray-400 rounded w-full">
                            @error('file') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white rounded">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
