<div>
    <a class="btn btn-green" wire:click="$set('open', true)"><i class="fas fa-edit"></i></a>

    <x-jet-dialog-modal wire:model='open'>

        <x-slot name='title'>
            Editar Post
        </x-slot>

        <x-slot name='content'>
            <div wire:loading wire:target='image'
                class="mb-4 bg-red-300 border border-red-700 text-red-700 px-4 py-3 rounded relative">
                <strong class="font-bold">Cargando imagen..</strong>
                <span class="block sm:inline">Espere..</span>
            </div>
            @if ($image)
                <img class="mb-4" src="{{ $image->temporaryUrl() }}" alt="">
            @else
                <img class="mb-4" src="{{ Storage::url($post->image) }}" alt="">
            @endif
            <div class="mb-4">
                <x-jet-label value='Título' />
                <x-jet-input wire:model='post.title' type='text' class="w-full" />
            </div>
            <div class="mb-4">
                <x-jet-label value='Contenido' />
                <textarea wire:model='post.content' rows="6" class="form-control w-full"></textarea>
            </div>
            <div>
                <input type="file" wire:model='image' id="{{ $identificador }}">
            </div>
        </x-slot>

        <x-slot name='footer'>
            <x-jet-secondary-button wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>
            <x-jet-danger-button wire:click="save" wire:loading.attr="disabled" class="disabled:opacity-25">
                Actualizar
            </x-jet-danger-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
