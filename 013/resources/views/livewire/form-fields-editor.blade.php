<x-slot name="header">
    <h2 class="text-xl font-semibold">Editar Campos - {{ $form->title }}</h2>
</x-slot>

<div class="max-w-4xl mx-auto py-10 space-y-6">
    <form wire:submit.prevent="addField" class="bg-white p-4 rounded shadow space-y-4">
        <div>
            <label class="block font-medium">Label</label>
            <input type="text" wire:model="label" class="w-full border-gray-300 rounded" />
        </div>

        <div>
            <label class="block font-medium">Tipo</label>
            <select wire:model="type" wire:change="$refresh" class="w-full border-gray-300 rounded">
                <option value="text">Texto</option>
                <option value="textarea">Área de Texto</option>
                <option value="number">Número</option>
                <option value="email">E-mail</option>
                <option value="select">Select</option>
                <option value="radio">Radio</option>
            </select>
        </div>

        @if(in_array($type, ['select', 'radio']))
            <div>
                <label class="block font-medium">Opções (separadas por vírgula)</label>
                <input type="text" wire:model="options" class="w-full border-gray-300 rounded" />
            </div>
        @endif

        <div class="flex items-center space-x-2">
            <input type="checkbox" wire:model="required" />
            <label>Campo obrigatório?</label>
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Adicionar Campo</button>
    </form>

    <div class="bg-white p-4 shadow rounded">
        <h3 class="text-lg font-bold mb-2">Campos Adicionados</h3>
        @forelse($fields as $field)
            <div class="flex justify-between items-center py-2 border-b">
                <div>
                    <strong>{{ $field->label }}</strong> <small>({{ $field->type }})</small>
                    @if($field->required)
                        <span class="text-red-500 text-sm ml-1">[obrigatório]</span>
                    @endif
                </div>
                <button wire:click="deleteField({{ $field->id }})" class="text-red-600 text-sm">Remover</button>
            </div>
        @empty
            <p>Nenhum campo adicionado ainda.</p>
        @endforelse
    </div>
    <div class="bg-white p-4 rounded shadow mt-8">
        <h3 class="text-lg font-bold mb-4">Nova Regra Condicional</h3>

        <form wire:submit.prevent="addRule" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium">Se o campo</label>
                <select wire:model="source_field_id" class="w-full border rounded">
                    <option value="">Selecione</option>
                    @foreach($form->fields as $f)
                        <option value="{{ $f->id }}">{{ $f->label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium">for</label>
                <select wire:model="operator" class="w-full border rounded">
                    <option value="=">=</option>
                    <option value="!=">≠</option>
                    <option value="contains">contém</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium">o valor</label>
                <input type="text" wire:model="value" class="w-full border rounded" />
            </div>

            <div>
                <label class="block text-sm font-medium">então</label>
                <select wire:model="target_field_id" class="w-full border rounded">
                    <option value="">Selecione</option>
                    @foreach($form->fields as $f)
                        <option value="{{ $f->id }}">{{ $f->label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium">&nbsp;</label>
                <select wire:model="action" class="w-full border rounded">
                    <option value="show">Mostrar</option>
                    <option value="hide">Ocultar</option>
                </select>
            </div>

            <div class="col-span-1 md:col-span-5">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">
                    Adicionar Regra
                </button>
            </div>
        </form>
        <div class="bg-white p-4 mt-6 rounded shadow">
            <h3 class="text-lg font-bold mb-4">Regras Condicionais</h3>

            @forelse($form->rules as $rule)
                <div class="flex justify-between items-center border-b py-2">
                    <span>
                        Se <strong>{{ $form->fields->find($rule->source_field_id)?->label }}</strong>
                        {{ $rule->operator }} "<strong>{{ $rule->value }}</strong>",
                        então <strong>{{ $rule->action === 'show' ? 'mostrar' : 'ocultar' }}</strong>
                        <strong>{{ $form->fields->find($rule->target_field_id)?->label }}</strong>.
                    </span>
                    <button wire:click="deleteRule({{ $rule->id }})" class="text-red-500 text-sm">Remover</button>
                </div>
            @empty
                <p>Nenhuma regra definida.</p>
            @endforelse
        </div>
    </div>


</div>
