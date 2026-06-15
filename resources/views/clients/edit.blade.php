<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Client - {{ $client->name }}</h2>
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto px-4 sm:px-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sm:p-6">
            <form action="{{ route('clients.update', $client) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                @include('clients._form', ['budgets' => $budgets, 'statuses' => $statuses, 'client' => $client])

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button type="submit"
                            class="w-full sm:w-auto px-6 py-3 sm:py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition-colors">
                        Update Client
                    </button>
                    <a href="{{ route('clients.index') }}"
                       class="w-full sm:w-auto text-center px-6 py-3 sm:py-2 border border-gray-300 text-gray-600 text-sm rounded-lg hover:bg-gray-50 active:bg-gray-100 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>