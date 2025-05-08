<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FormIndex extends Component
{
    public $forms;

    public function mount()
    {
        $this->loadForms();
    }

    public function loadForms()
    {
        $this->forms = Auth::user()->forms()->latest()->get();
    }

    public function render()
    {
        return view('livewire.form-index')->layout('layouts.app');
    }
}
