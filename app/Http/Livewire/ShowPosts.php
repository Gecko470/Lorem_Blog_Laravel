<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ShowPosts extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $post, $image, $identificador;
    public $search = '';
    public $sort = 'id';
    public $direction = 'desc';
    protected $listeners = ['render', 'delete']; //['render' => 'render'];
    public $open_edit = false;
    public $cant = '10';
    public $readyToLoad = false;

    protected $queryString = [
        'cant' => ['except' => '10'],
        'sort' => ['except' => 'id'],
        'direction' => ['except' => 'desc'],
        'search' => ['except' => '']
    ];

    protected $rules = [
        'post.title' => 'required',
        'post.content' => 'required'
    ];

    public function mount()
    {
        $this->identificador = rand();
        $this->post = new Post();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function edit(Post $post)
    {
        $this->post = $post;
        $this->open_edit = true;
    }

    public function update()
    {
        $this->validate();

        if ($this->image) {
            Storage::delete([$this->post->image]);
            $this->post->image = $this->image->store('posts');
        }

        $this->post->save();

        $this->reset(['open_edit', 'image']);
        $this->identificador = rand();
        $this->emit('alert', 'Post actualizado correctamente..');
    }

    public function order($sort)
    {
        if ($this->sort == $sort) {
            if ($this->direction == 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }
        } else {
            $this->sort = $sort;
            $this->direction = 'asc';
        }
    }

    public function loadPosts()
    {
        $this->readyToLoad = true;
    }

    public function delete(Post $post)
    {
        $post->delete();
    }

    public function render()
    {
        if ($this->readyToLoad) {

            $posts = Post::where('title', 'like', '%' . $this->search . '%')
                ->orwhere('content', 'like', '%' . $this->search . '%')
                ->orderby($this->sort, $this->direction)
                ->paginate($this->cant);
        } else {
            $posts = [];
        }

        return view('livewire.show-posts', compact('posts'));
    }
}
