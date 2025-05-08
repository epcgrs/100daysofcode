<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;

class FormResponseController extends Controller
{
    public function index(Form $form)
    {
        // Garante que o usuário só veja seus próprios formulários
        $this->authorize('view', $form); // Opcional, se quiser usar policies

        $responses = $form->responses()->with('answers.field')->latest()->get();

        return view('form.responses', compact('form', 'responses'));
    }
}
