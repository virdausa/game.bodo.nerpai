<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipmentConfirmationProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'confirmation_id',
        'product_id',
        'quantity',
        'condition',
        'notes'
    ];

    // Relationships
    public function confirmation(): BelongsTo
    {
        return $this->belongsTo(ShipmentConfirmation::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
