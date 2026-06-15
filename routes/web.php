<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('clients.index'));

Route::middleware('auth')->group(function () {
    Route::resource('clients', ClientController::class);

    Route::patch('clients/{client}/status', [ClientController::class, 'updateStatus'])
        ->name('clients.updateStatus');
});

require __DIR__.'/auth.php';
