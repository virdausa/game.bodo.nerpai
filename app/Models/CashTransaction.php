<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_number',
        'date',
        'account_id',
        'type',
        'source',
        'source_id',
        'amount',
        'status',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function source(): MorphTo
    {
        return $this->morphTo(null, 'source', 'source_id');
    }

    // Helper to generate transaction number
    public static function generateTransactionNumber(): string
    {
        $date = now()->format('Ymd');
        $latest = static::whereDate('created_at', today())->count();
        return 'CASH-' . $date . '-' . str_pad($latest + 1, 4, '0', STR_PAD_LEFT);
    }
}
