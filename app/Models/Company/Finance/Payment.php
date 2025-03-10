<?php

namespace App\Models\Company\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'number',
        'type',
        'source_type',
        'source_id',
        'date',
        'payment_date',
        'total_amount',
        'payment_method',
        'status',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'total_amount' => 'decimal:2',
    ];


    public function generateNumber()
    {
        $this->number = 'PYM_' . $this->type . '_' . $this->id;
        return $this->number;
    }

    // Relationships
    public function source(): BelongsTo
    {
        return $this->morphTo();
    }

    public function payment_details(): HasMany
    {
        return $this->hasMany(PaymentDetail::class);
    }
}
