<?php

namespace App\Models\Store;

use App\Models\Product;
use App\Models\Company\Store;
use App\Models\WarehouseLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreInventory extends Model
{
    use HasFactory;

    protected $table = 'store_inventories';
    public $timestamps = true;

    protected $fillable = [
        'store_id',
        'product_id',
        'warehouse_location_id',
        'expire_date',
        'quantity',
        'reserved_quantity',
        'in_transit_quantity'
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouseLocation(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class);
    }
}
