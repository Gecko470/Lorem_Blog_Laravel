<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class EditPost extends Component
{
    use WithFileUploads;

    public $url;
    public $post, $image, $identificador;
    public $open = false;
    protected $rules = [
        'post.title' => 'required',
        'post.content' => 'required'
    ]; //IMPORTANTE HACER ESTO!! Si no no me deja vincular con wire:model desde la vista.

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->identificador = rand();
    }

    public function save()
    {
        $this->validate();

        if ($this->image) {
            Storage::delete([$this->post->image]);
            $this->post->image = $this->image->store('posts');
        }

        $this->post->save();

        $this->reset(['open', 'image']);
        $this->identificador = rand();
        $this->emitTo('show-posts', 'render');
        $this->emit('alert', 'Post actualizado correctamente..');
    }

    public function render()
    {
        return view('livewire.edit-post');
    }
}
