<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 20);
            $table->enum('budget', [
                'under_10k',
                '10k_50k',
                '50k_100k',
                'above_100k',
            ])->default('under_10k');
            $table->enum('status', [
                'new_lead',
                'waiting',
                'meeting',
                'follow_up',
                'closed',
                'lost',
            ])->default('new_lead');
            $table->text('note')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};