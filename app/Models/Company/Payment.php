<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'type_id',
        'date',
        'amount',
        'method',
        'status',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function payable(): BelongsTo
    {
        return $this->type === 'AP'
            ? $this->supplier()
            : $this->customer();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'type_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'type_id');
    }
}
