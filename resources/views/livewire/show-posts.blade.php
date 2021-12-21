<div wire:init="loadPosts">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lorem Blog') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <x-tabla>
            <div class="px-6 py-4 flex items-center">
                <div class="flex items-center">
                    <span class="mr-2">Mostrar</span>
                    <select wire:model="cant" class="form-control">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="ml-2">registros</span>
                </div>
                <x-jet-input class="flex-1 ml-2" placeholder="Introduce término de búsqueda.." type="text"
                    wire:model="search" />
                @livewire('create-post')
            </div>
            @if (count($posts))
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">

                    <th wire:click="order('id')" scope="col"
                        class="w-28 cursor-pointer px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                        @if ($sort == 'id')
                        @if ($direction == 'asc')
                        <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                        @else
                        <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                        @endif
                        @else
                        <i class="fas fa-sort float-right mt-1"></i>
                        @endif
                    </th>
                    <th wire:click="order('title')" scope="col"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Title
                        @if ($sort == 'title')
                        @if ($direction == 'asc')
                        <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                        @else
                        <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                        @endif
                        @else
                        <i class="fas fa-sort float-right mt-1"></i>
                        @endif
                    </th>
                    <th wire:click="order('content')" scope="col"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Content
                        @if ($sort == 'content')
                        @if ($direction == 'asc')
                        <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                        @else
                        <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                        @endif
                        @else
                        <i class="fas fa-sort float-right mt-1"></i>
                        @endif
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Edit</span>
                    </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($posts as $item)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $item->id }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $item->title }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{!! $item->content !!}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex">
                            {{-- @livewire('edit-post', ['post' => $post], key($post->id)) --}}
                            <a class="btn btn-green" wire:click="edit({{ $item }})"><i class="fas fa-edit"></i></a>
                            <a class="btn btn-red ml-2" wire:click="$emit('deletePost', {{ $item->id }})"><i
                                    class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
                    <!-- More people... -->
                </tbody>
            </table>

            @if ($posts->hasPages())
            <div class="px-6 py-3">
                {{ $posts->links() }}
            </div>
            @endif

            @else
            <div class="px-6 py-4">
                No existe ningún registro coincidente con ese término de búsqueda..
            </div>
            @endif

        </x-tabla>
    </div>

    <x-jet-dialog-modal wire:model='open_edit'>

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
            <x-jet-secondary-button wire:click="$set('open_edit', false)">
                Cancelar
            </x-jet-secondary-button>
            <x-jet-danger-button wire:click="update" wire:loading.attr="disabled" class="disabled:opacity-25">
                Actualizar
            </x-jet-danger-button>
        </x-slot>

    </x-jet-dialog-modal>

    @push('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        Livewire.on('deletePost', postId => {

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('show-posts', 'delete', postId);
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    }
                })

            });
    </script>
    @endpush
</div>