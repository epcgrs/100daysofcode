
<div class="max-w-2xl mx-auto py-10">
    <h2 class="text-2xl font-semibold mb-6">Novo Formulário</h2>

    <form wire:submit.prevent="createForm" class="space-y-6">
        <div>
            <label class="block font-medium">Título</label>
            <input type="text" wire:model="title" class="mt-1 block w-full border-gray-300 rounded" />
            @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium">Slug (URL)</label>
            <input type="text" wire:model="slug" class="mt-1 block w-full border-gray-300 rounded" />
        </div>

        <div>
            <label class="block font-medium">Descrição</label>
            <textarea wire:model="description" class="mt-1 block w-full border-gray-300 rounded"></textarea>
        </div>

        <div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                Criar Formulário
            </button>
        </div>
    </form>
</div>
