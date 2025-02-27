<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipmentPacking extends Model
{
    protected $table = 'shipment_packings';

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shipment_id',
        'weight',
        'volume',
        'notes'
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'volume' => 'decimal:2',
    ];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function packing_products(): belongsToMany
    {
        return $this->belongsToMany(Product::class, 'packing_products')
                    ->withPivot('quantity', 'packing_weight', 'packing_volume')
                    ->withTimestamps();
    }
}
