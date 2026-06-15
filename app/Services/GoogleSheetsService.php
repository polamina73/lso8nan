<?php

namespace App\Services;

use App\Models\Client;
use Revolution\Google\Sheets\Facades\Sheets;

class GoogleSheetsService
{
    protected string $spreadsheetId;

    protected string $sheet = 'Sheet1';

    public function __construct()
    {
        $this->spreadsheetId = config('services.google.spreadsheet_id');
    }

    /**
     * Full sync: clears the sheet and rewrites all clients.
     */
    public function syncAll(): void
    {
        $clients = Client::orderBy('id')->get();

        $rows = [
            ['ID', 'Name', 'Phone', 'Budget', 'Status', 'Note', 'Last Updated'],
        ];

        foreach ($clients as $client) {
            $rows[] = [
                $client->id,
                $client->name,
                $client->phone,
                $client->budget_label,
                $client->status_label,
                $client->note ?? '',
                $client->updated_at->format('Y-m-d H:i'),
            ];
        }

        Sheets::spreadsheet($this->spreadsheetId)
            ->sheet($this->sheet)
            ->clear()
            ->append($rows);

        Client::query()->update(['synced_at' => now()]);
    }
}