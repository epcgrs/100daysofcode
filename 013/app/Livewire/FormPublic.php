<?php

namespace App\Livewire;

use App\Models\Form;
use App\Models\FormAnswer;
use App\Models\FormResponse;
use Livewire\Component;

class FormPublic extends Component
{
    public Form $form;
    public $fields = [];
    public $answers = [];

    public function mount($slug)
    {
        $this->form = Form::where('slug', $slug)->with(['fields', 'rules'])->firstOrFail();

        foreach ($this->form->fields as $field) {
            $this->answers[$field->id] = '';
        }
    }

    public function submit()
    {
        $this->validate($this->validationRules());

        $response = FormResponse::create([
            'form_id' => $this->form->id,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        foreach ($this->answers as $field_id => $value) {
            FormAnswer::create([
                'form_response_id' => $response->id,
                'field_id' => $field_id,
                'answer' => is_array($value) ? implode(', ', $value) : $value,
            ]);
        }

        session()->flash('message', 'Resposta enviada com sucesso!');
        return redirect()->route('form.public', ['slug' => $this->form->slug]);
    }

    protected function validationRules()
    {
        $rules = [];
        foreach ($this->form->fields as $field) {
            if ($field->required) {
                $rules["answers.{$field->id}"] = 'required';
            }
        }
        return $rules;
    }

    public function render()
    {
        return view('livewire.form-public', [
            'rulesJson' => json_encode($this->form->rules),
        ])->layout('layouts.app');
    }
}
