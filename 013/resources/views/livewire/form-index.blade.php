<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Meus Formulários</h2>
        <a href="{{ route('form.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Novo Formulário</a>
    </div>

    @forelse($forms as $form)
        <div class="p-4 bg-white shadow rounded mb-2">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold">{{ $form->title }}</h3>
                    <p class="text-sm text-gray-600">{{ $form->slug }}</p>
                </div>
                <div class="space-x-2">
                    <a href="{{ route('form.edit', $form) }}" class="text-blue-600 hover:underline">Editar</a>
                    <a href="{{ route('form.responses', $form) }}" class="text-green-600 hover:underline">Respostas</a>
                </div>
            </div>
        </div>
    @empty
        <p>Você ainda não criou nenhum formulário.</p>
    @endforelse
</div>
