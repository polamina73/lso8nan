<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
    <input type="text" name="name" value="{{ old('name', $client->name ?? '') }}"
           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('name') border-red-400 @enderror"
           placeholder="e.g. Ahmed Hassan">
    @error('name')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
    <input type="text" name="phone" value="{{ old('phone', $client->phone ?? '') }}"
           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('phone') border-red-400 @enderror"
           placeholder="e.g. +20 100 123 4567"
           inputmode="tel">
    @error('phone')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Budget</label>
    <select name="budget"
            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
        @foreach ($budgets as $value => $label)
            <option value="{{ $value }}" @selected(old('budget', $client->budget ?? '') === $value)>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('budget')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
    <select name="status"
            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
        @foreach ($statuses as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $client->status ?? 'new_lead') === $value)>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('status')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Note</label>
    <textarea name="note" rows="4"
              class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('note') border-red-400 @enderror"
              placeholder="Optional notes about this client...">{{ old('note', $client->note ?? '') }}</textarea>
    @error('note')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
