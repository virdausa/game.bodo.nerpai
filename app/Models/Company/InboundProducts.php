<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

use App\Models\Company\Inbound;
use App\Models\Company\Product;
use App\Models\Company\WarehouseLocation;

class InboundProducts extends Model
{
    protected $table = 'inbound_products';

    protected $fillable = [
        'inbound_id',
        'product_id',
        'quantity',
        'warehouse_location_id',
        'cost_per_unit',
        'notes',
    ];

    public function inbound()
    {
        return $this->belongsTo(Inbound::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse_location()
    {
        return $this->belongsTo(WarehouseLocation::class);
    }
}
