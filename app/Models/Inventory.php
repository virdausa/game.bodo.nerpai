<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Product;
use App\Models\Company\Warehouse;
use App\Models\Company\WarehouseLocation;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventories';

    protected $fillable = [
        'product_id',
        'warehouse_id',
		'warehouse_location_id',
        'quantity',
        'reserved_quantity',
        'in_transit_quantity',
    ];

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship with Warehouse
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
	
	// Define relationship with Location
    public function warehouse_location()
    {
        return $this->belongsTo(WarehouseLocation::class);
    }
}
