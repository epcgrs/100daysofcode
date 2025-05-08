<x-slot name="header">
    <h2 class="text-xl font-semibold">{{ $form->title }}</h2>
</x-slot>

<div class="max-w-3xl mx-auto py-10">
    @if (session()->has('message'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form  wire:submit.prevent="submit"
        x-data="formLogic({{ $rulesJson }})"
        class="space-y-6 bg-white p-6 rounded shadow">
        @foreach($form->fields as $field)
            <div x-show="isVisible({{ $field->id }})" x-transition>
                <label class="block font-medium">{{ $field->label }}</label>

                @switch($field->type)
                    @case('text')
                    @case('email')
                    @case('number')
                        <input type="{{ $field->type }}" wire:model="answers.{{ $field->id }}" class="w-full border-gray-300 rounded"
                        @input="updateValue({{ $field->id }}, $event.target.value)"/>
                        @break

                    @case('textarea')
                        <textarea wire:model="answers.{{ $field->id }}" class="w-full border-gray-300 rounded"
                            @input="updateValue({{ $field->id }}, $event.target.value)"></textarea>
                        @break

                    @case('select')
                        <select wire:model="answers.{{ $field->id }}" class="w-full border-gray-300 rounded"
                            @change="updateValue({{ $field->id }}, $event.target.value)">
                            <option value="">Selecione</option>
                            @foreach($field->options as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </select>
                        @break

                    @case('radio')
                        @foreach($field->options as $opt)
                            <label class="block">
                                <input type="radio" wire:model="answers.{{ $field->id }}" value="{{ $opt }}" />
                                {{ $opt }}
                            </label>
                        @endforeach
                        @break
                @endswitch

                @error("answers.{$field->id}")
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        @endforeach

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
            Enviar
        </button>
    </form>
</div>
<script>
    function formLogic(rules) {
        return {
            values: {},
            rules: rules,
            isVisible(id) {
                let relevantRules = this.rules.filter(r => r.target_field_id === id);

                if (relevantRules.length === 0) return true;

                return relevantRules.every(rule => {
                    const val = this.values[rule.source_field_id] ?? '';
                    switch (rule.operator) {
                        case '=': return val == rule.value;
                        case '!=': return val != rule.value;
                        case 'contains': return val.includes(rule.value);
                        default: return true;
                    }
                });
            },
            updateValue(id, value) {
                this.values[id] = value;
            }
        }
    }
</script>
