<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Clients</h2>
            <a href="{{ route('clients.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">
                + Add Client
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" class="flex gap-3 mb-6 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search name or phone..."
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-56 focus:outline-none focus:ring-2 focus:ring-indigo-400">

            <select name="status"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <option value="">All statuses</option>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                    class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">
                Filter
            </button>

            @if (request('search') || request('status'))
                <a href="{{ route('clients.index') }}"
                   class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 self-center">
                    Clear
                </a>
            @endif
        </form>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Budget</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Note</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quick Actions</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($clients as $client)
                        <tr class="hover:bg-gray-50"
                            x-data="statusRow({{ $client->id }}, '{{ $client->status }}', '{{ $client->status_label }}', '{{ $client->status_color }}')"
                            id="row-{{ $client->id }}">

                            <td class="px-4 py-3 font-medium text-gray-900">{{ $client->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $client->phone }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $client->budget_label }}</td>

                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      :class="badgeClass">
                                    <span x-text="statusLabel"></span>
                                </span>
                            </td>

                            <td class="px-4 py-3 text-gray-500 max-w-xs truncate">
                                {{ $client->note ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($statuses as $value => $label)
                                        <button
                                            x-show="currentStatus !== '{{ $value }}'"
                                            @click="setStatus('{{ $value }}')"
                                            class="px-2 py-1 text-xs rounded border border-gray-300 text-gray-600 hover:bg-indigo-50 hover:border-indigo-400 hover:text-indigo-700 transition-colors">
                                            -&gt; {{ $label }}
                                        </button>
                                    @endforeach
                                </div>
                            </td>

                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('clients.edit', $client) }}"
                                   class="text-indigo-600 hover:text-indigo-800 mr-3 text-xs font-medium">
                                    Edit
                                </a>
                                <form action="{{ route('clients.destroy', $client) }}" method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Delete this client?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-500 hover:text-red-700 text-xs font-medium">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-gray-400">
                                No clients found.
                                <a href="{{ route('clients.create') }}" class="text-indigo-500 hover:underline ml-1">Add one?</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $clients->links() }}
        </div>
    </div>

    <script>
        function statusRow(id, initialStatus, initialLabel, initialColor) {
            const colorMap = {
                gray: 'bg-gray-100 text-gray-700',
                yellow: 'bg-yellow-100 text-yellow-700',
                blue: 'bg-blue-100 text-blue-700',
                purple: 'bg-purple-100 text-purple-700',
                green: 'bg-green-100 text-green-700',
                red: 'bg-red-100 text-red-700',
            };

            const colorKeys = {
                new_lead: 'gray',
                waiting: 'yellow',
                meeting: 'blue',
                follow_up: 'purple',
                closed: 'green',
                lost: 'red',
            };

            return {
                currentStatus: initialStatus,
                statusLabel: initialLabel,
                badgeClass: colorMap[initialColor] || colorMap.gray,

                async setStatus(newStatus) {
                    try {
                        const res = await fetch(`/clients/${id}/status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ status: newStatus }),
                        });

                        const data = await res.json();
                        if (data.success) {
                            this.currentStatus = data.status;
                            this.statusLabel = data.status_label;
                            this.badgeClass = colorMap[colorKeys[data.status]] || colorMap.gray;
                        }
                    } catch (e) {
                        console.error('Status update failed', e);
                    }
                },
            };
        }
    </script>
</x-app-layout>