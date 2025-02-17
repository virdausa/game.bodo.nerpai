<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackingProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'packing_id',
        'product_id',
        'quantity',
        'packing_weight',
        'packing_volume',
        'notes'
    ];

    protected $casts = [
        'packing_weight' => 'decimal:2',
        'packing_volume' => 'decimal:2',
    ];

    public function shipmentPacking(): BelongsTo
    {
        return $this->belongsTo(ShipmentPacking::class, 'packing_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
