<?php

namespace App\Livewire;

use App\Models\Form;
use Livewire\Component;

class FormFieldsEditor extends Component
{
    public Form $form;

    public $label = '';
    public $type = 'text';
    public $required = false;
    public $options = '';
    public $operator = '=';
    public $value;
    public $target_field_id;
    public $action = 'show';
    public $source_field_id;

    protected $rules = [
        'label' => 'required|string|max:255',
        'type' => 'required|string',
        'required' => 'boolean',
        'options' => 'nullable|string',
    ];

    public function addField()
    {
        $this->validate();

        $this->form->fields()->create([
            'label' => $this->label,
            'type' => $this->type,
            'required' => $this->required,
            'options' => $this->type === 'select' || $this->type === 'radio'
                ? explode(',', $this->options)
                : null,
            'order' => $this->form->fields()->count(),
        ]);

        $this->reset(['label', 'type', 'required', 'options']);
    }

    public function deleteField($id)
    {
        $this->form->fields()->where('id', $id)->delete();
    }

    public function addRule()
    {
        $this->validate([
            'source_field_id' => 'required|exists:form_fields,id',
            'operator' => 'required|string',
            'value' => 'required|string',
            'target_field_id' => 'required|exists:form_fields,id',
            'action' => 'required|string',
        ]);

        $this->form->rules()->create([
            'source_field_id' => $this->source_field_id,
            'operator' => $this->operator,
            'value' => $this->value,
            'target_field_id' => $this->target_field_id,
            'action' => $this->action,
        ]);

        $this->reset(['source_field_id', 'operator', 'value', 'target_field_id', 'action']);
    }

    public function deleteRule($id)
    {
        $this->form->rules()->where('id', $id)->delete();
    }


    public function render()
    {
        return view('livewire.form-fields-editor', [
            'fields' => $this->form->fields()->orderBy('order')->get(),
        ])->layout('layouts.app');
    }
}
