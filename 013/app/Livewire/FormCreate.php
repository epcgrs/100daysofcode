<?php

namespace App\Livewire;

use App\Models\Form;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FormCreate extends Component
{
    public $title;
    public $description;
    public $slug;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
    ];

    public function updatedTitle()
    {
        $this->slug = \Str::slug($this->title);
    }

    public function createForm()
    {
        $this->validate();

        $form = Form::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description,
            'slug' => $this->slug ?: \Str::slug($this->title),
        ]);

        session()->flash('message', 'Formulário criado com sucesso!');
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.form-create')->layout('layouts.app');
    }
}
