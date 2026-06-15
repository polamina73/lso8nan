<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Client - {{ $client->name }}</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form action="{{ route('clients.update', $client) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                @include('clients._form', ['budgets' => $budgets, 'statuses' => $statuses, 'client' => $client])

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">
                        Update Client
                    </button>
                    <a href="{{ route('clients.index') }}"
                       class="px-5 py-2 border border-gray-300 text-gray-600 text-sm rounded-lg hover:bg-gray-50">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>