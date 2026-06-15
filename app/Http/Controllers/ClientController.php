<?php

namespace App\Http\Controllers;

use App\Jobs\SyncClientsToSheets;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', 'like', '%'.$request->search.'%');
            });
        }

        $clients = $query->latest()->paginate(20)->withQueryString();

        return view('clients.index', [
            'clients' => $clients,
            'statuses' => Client::$statusLabels,
            'statusColors' => Client::$statusColors,
        ]);
    }

    public function create()
    {
        return view('clients.create', [
            'budgets' => Client::$budgetLabels,
            'statuses' => Client::$statusLabels,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone'  => 'required|string|max:20|unique:clients,phone',
            'budget' => 'required|in:'.implode(',', array_keys(Client::$budgetLabels)),
            'status' => 'required|in:'.implode(',', array_keys(Client::$statusLabels)),
            'note' => 'nullable|string|max:1000',
        ]);

        Client::create($validated);
        SyncClientsToSheets::dispatch();

        return redirect()->route('clients.index')
            ->with('success', 'Client added successfully.');
    }

    public function edit(Client $client)
    {
        return view('clients.edit', [
            'client' => $client,
            'budgets' => Client::$budgetLabels,
            'statuses' => Client::$statusLabels,
        ]);
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone'  => 'required|string|max:20|unique:clients,phone,'.$client->id,
            'budget' => 'required|in:'.implode(',', array_keys(Client::$budgetLabels)),
            'status' => 'required|in:'.implode(',', array_keys(Client::$statusLabels)),
            'note' => 'nullable|string|max:1000',
        ]);

        $client->update($validated);
        SyncClientsToSheets::dispatch();

        return redirect()->route('clients.index')
            ->with('success', 'Client updated.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        SyncClientsToSheets::dispatch();

        return redirect()->route('clients.index')
            ->with('success', 'Client deleted.');
    }

    public function updateStatus(Request $request, Client $client)
    {
        $request->validate([
            'status' => 'required|in:'.implode(',', array_keys(Client::$statusLabels)),
        ]);

        $client->update(['status' => $request->status]);
        SyncClientsToSheets::dispatch();

        return response()->json([
            'success' => true,
            'status' => $client->status,
            'status_label' => $client->status_label,
            'status_color' => $client->status_color,
        ]);
    }
}