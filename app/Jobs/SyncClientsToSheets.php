<?php

namespace App\Jobs;

use App\Services\GoogleSheetsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncClientsToSheets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 10;

    public function handle(GoogleSheetsService $service): void
    {
        try {
            $service->syncAll();
        } catch (\Throwable $e) {
            Log::error('Google Sheets sync failed: '.$e->getMessage());
            $this->fail($e);
        }
    }
}