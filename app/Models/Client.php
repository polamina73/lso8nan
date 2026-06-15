<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'budget',
        'status',
        'note',
        'synced_at',
    ];

    protected $casts = [
        'synced_at' => 'datetime',
    ];

    public static array $budgetLabels = [
        'under_10k' => 'Under 10K',
        '10k_50k' => '10K - 50K',
        '50k_100k' => '50K - 100K',
        'above_100k' => 'Above 100K',
    ];

    public static array $statusLabels = [
        'new_lead' => 'New Lead',
        'waiting' => 'Waiting',
        'meeting' => 'Meeting',
        'follow_up' => 'Follow-up',
        'closed' => 'Closed',
        'lost' => 'Lost',
    ];

    public static array $statusColors = [
        'new_lead' => 'gray',
        'waiting' => 'yellow',
        'meeting' => 'blue',
        'follow_up' => 'purple',
        'closed' => 'green',
        'lost' => 'red',
    ];

    public function getBudgetLabelAttribute(): string
    {
        return self::$budgetLabels[$this->budget] ?? $this->budget;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::$statusLabels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::$statusColors[$this->status] ?? 'gray';
    }
}