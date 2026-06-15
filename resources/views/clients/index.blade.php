<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Clients</h2>
            <a href="{{ route('clients.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition-colors">
                + Add Client
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Search & Filter --}}
        <form method="GET" class="mb-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search name or phone..."
                       class="border border-gray-300 rounded-lg px-3 py-2.5 text-sm flex-1 sm:max-w-xs focus:outline-none focus:ring-2 focus:ring-indigo-400">

                <select name="status"
                        class="border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <div class="flex gap-2">
                    <button type="submit"
                            class="flex-1 sm:flex-none px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-colors">
                        Filter
                    </button>
                    @if (request('search') || request('status'))
                        <a href="{{ route('clients.index') }}"
                           class="flex-1 sm:flex-none px-4 py-2.5 text-sm text-center text-gray-500 hover:text-gray-700 rounded-lg border border-gray-200 hover:bg-gray-50 active:bg-gray-100 transition-colors">
                            Clear
                        </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- ===== MOBILE CARD VIEW (hidden on md+) ===== --}}
        <div class="block md:hidden space-y-3">
            @forelse ($clients as $client)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4"
                     x-data="statusRow({{ $client->id }}, '{{ $client->status }}', '{{ $client->status_label }}', '{{ $client->status_color }}')"
                     id="card-{{ $client->id }}">

                    {{-- Name + Status badge --}}
                    <div class="flex items-start justify-between mb-2">
                        <div class="min-w-0 mr-3">
                            <div class="font-semibold text-gray-900 text-base truncate">{{ $client->name }}</div>
                            <a href="tel:{{ $client->phone }}"
                               class="text-sm text-indigo-600 hover:underline">{{ $client->phone }}</a>
                        </div>
                        <span class="inline-flex shrink-0 items-center px-2.5 py-1 rounded-full text-xs font-medium"
                              :class="badgeClass">
                            <span x-text="statusLabel"></span>
                        </span>
                    </div>

                    <a href="{{ $client->whatsapp_url ? $client->whatsapp_url : '#' }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="mb-3 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-green-700 active:bg-green-800 {{ $client->whatsapp_url ? '' : 'pointer-events-none opacity-40' }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.6 13.2a1 1 0 00-.7.2l-1.5 1a8.2 8.2 0 01-3.6-3.6l1-1.5a1 1 0 00.2-.7l-.8-2.7a1 1 0 00-1.2-.7l-2.2.6a1 1 0 00-.7.9c0 6.1 4.9 11 11 11a1 1 0 00.9-.7l.6-2.2a1 1 0 00-.7-1.2l-2.3-.4z"/></svg>
                        WhatsApp
                    </a>

                    {{-- Budget --}}
                    <div class="text-sm text-gray-600 mb-2">
                        <span class="text-gray-400">Budget:</span> {{ $client->budget_label }}
                    </div>

                    {{-- Note --}}
                    @if($client->note)
                        <div class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $client->note }}</div>
                    @endif

                    {{-- Quick status buttons (horizontally scrollable) --}}
                    <div class="text-xs text-gray-400 mb-1.5 font-medium">Move to:</div>
                    <div class="flex gap-1.5 overflow-x-auto scrollbar-hide pb-1 mb-3">
                        @php
                            $btnColors = [
                                'new_lead'  => 'border-gray-300  text-gray-600  bg-gray-50  active:bg-gray-200',
                                'waiting'   => 'border-yellow-300 text-yellow-700 bg-yellow-50 active:bg-yellow-100',
                                'meeting'   => 'border-blue-300  text-blue-700  bg-blue-50  active:bg-blue-100',
                                'follow_up' => 'border-purple-300 text-purple-700 bg-purple-50 active:bg-purple-100',
                                'closed'    => 'border-green-300  text-green-700  bg-green-50  active:bg-green-100',
                                'lost'      => 'border-red-300    text-red-700    bg-red-50    active:bg-red-100',
                            ];
                        @endphp
                        @foreach ($statuses as $value => $label)
                            <button
                                x-show="currentStatus !== '{{ $value }}'"
                                @click="setStatus('{{ $value }}')"
                                class="shrink-0 px-3 py-1.5 text-xs font-medium rounded-lg border transition-colors whitespace-nowrap {{ $btnColors[$value] ?? 'border-gray-300 text-gray-600 bg-gray-50' }}">
                                → {{ $label }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Edit / Delete --}}
                    <div class="grid grid-cols-2 gap-2 pt-3 border-t border-gray-100">
                        <a href="{{ route('clients.edit', $client) }}"
                           class="inline-flex items-center justify-center gap-1.5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.293-6.293a1 1 0 011.414 0l1.586 1.586a1 1 0 010 1.414L12 16H9v-3z"/></svg>
                            Edit
                        </a>
                        <form action="{{ route('clients.destroy', $client) }}" method="POST" class="flex-1"
                              onsubmit="return confirm('Delete this client?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-600 active:bg-red-700 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-200 p-10 text-center text-gray-400">
                    No clients found.
                    <a href="{{ route('clients.create') }}" class="text-indigo-500 hover:underline ml-1">Add one?</a>
                </div>
            @endforelse
        </div>

        {{-- ===== DESKTOP TABLE VIEW (hidden on mobile) ===== --}}
        <div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
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
                                    @php
                                        $btnColors = [
                                            'new_lead'  => 'border-gray-300  text-gray-600  hover:bg-gray-100',
                                            'waiting'   => 'border-yellow-300 text-yellow-700 hover:bg-yellow-50',
                                            'meeting'   => 'border-blue-300  text-blue-700  hover:bg-blue-50',
                                            'follow_up' => 'border-purple-300 text-purple-700 hover:bg-purple-50',
                                            'closed'    => 'border-green-300  text-green-700  hover:bg-green-50',
                                            'lost'      => 'border-red-300    text-red-700    hover:bg-red-50',
                                        ];
                                    @endphp
                                    @foreach ($statuses as $value => $label)
                                        <button
                                            x-show="currentStatus !== '{{ $value }}'"
                                            @click="setStatus('{{ $value }}')"
                                            class="px-2 py-1 text-xs font-medium rounded-md border transition-colors {{ $btnColors[$value] ?? 'border-gray-300 text-gray-600' }}">
                                            → {{ $label }}
                                        </button>
                                    @endforeach
                                </div>
                            </td>

                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <a href="{{ $client->whatsapp_url ? $client->whatsapp_url : '#' }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-emerald-500 rounded-md hover:bg-emerald-600 transition-colors mr-1.5 {{ $client->whatsapp_url ? '' : 'pointer-events-none opacity-40' }}">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.6 13.2a1 1 0 00-.7.2l-1.5 1a8.2 8.2 0 01-3.6-3.6l1-1.5a1 1 0 00.2-.7l-.8-2.7a1 1 0 00-1.2-.7l-2.2.6a1 1 0 00-.7.9c0 6.1 4.9 11 11 11a1 1 0 00.9-.7l.6-2.2a1 1 0 00-.7-1.2l-2.3-.4z"/></svg>
                                    WhatsApp
                                </a>
                                <a href="{{ route('clients.edit', $client) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition-colors mr-1.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.293-6.293a1 1 0 011.414 0l1.586 1.586a1 1 0 010 1.414L12 16H9v-3z"/></svg>
                                    Edit
                                </a>
                                <form action="{{ route('clients.destroy', $client) }}" method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Delete this client?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-red-500 rounded-md hover:bg-red-600 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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
                gray:   'bg-gray-100 text-gray-700',
                yellow: 'bg-yellow-100 text-yellow-700',
                blue:   'bg-blue-100 text-blue-700',
                purple: 'bg-purple-100 text-purple-700',
                green:  'bg-green-100 text-green-700',
                red:    'bg-red-100 text-red-700',
            };

            const colorKeys = {
                new_lead:  'gray',
                waiting:   'yellow',
                meeting:   'blue',
                follow_up: 'purple',
                closed:    'green',
                lost:      'red',
            };

            return {
                currentStatus: initialStatus,
                statusLabel:   initialLabel,
                badgeClass:    colorMap[initialColor] || colorMap.gray,

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
                            this.statusLabel   = data.status_label;
                            this.badgeClass    = colorMap[colorKeys[data.status]] || colorMap.gray;
                        }
                    } catch (e) {
                        console.error('Status update failed', e);
                    }
                },
            };
        }
    </script>
</x-app-layout>
