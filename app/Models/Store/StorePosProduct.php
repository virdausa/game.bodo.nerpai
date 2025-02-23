<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StorePosProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'store_pos_products';
    protected $timestamps = true;

    protected $fillable = [
        'store_pos_id',
        'store_product_id',
        'quantity',
        'price',
        'subtotal',
        'notes'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function storePos(): BelongsTo
    {
        return $this->belongsTo(StorePos::class);
    }

    public function storeProduct(): BelongsTo
    {
        return $this->belongsTo(StoreProduct::class);
    }
}
