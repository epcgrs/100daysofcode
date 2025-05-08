<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Respostas: {{ $form->title }}</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 space-y-6">
        @forelse($responses as $response)
            <div class="p-4 bg-white rounded shadow">
                <p class="text-sm text-gray-500">
                    Enviado em {{ $response->submitted_at->format('d/m/Y H:i') }}
                    - IP: {{ $response->ip }}
                </p>

                <ul class="mt-2 space-y-2">
                    @foreach($response->answers as $answer)
                        <li>
                            <strong>{{ $answer->field->label }}:</strong>
                            {{ $answer->answer }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @empty
            <p>Nenhuma resposta ainda.</p>
        @endforelse
    </div>
</x-app-layout>
