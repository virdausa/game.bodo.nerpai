<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

use App\Models\Company\Product;
use App\Models\Company\WarehouseLocation;
use App\Models\Company\Outbound;
use App\Models\Inventory;

class OutboundProduct extends Model
{
	protected $fillable = [
		'outbound_id',
		'product_id',
		'warehouse_location_id',
		'quantity',
		'cost_per_unit',
		'total_cost',
		'notes',
	];

	public function outbound() {
		return $this->belongsTo(Outbound::class);
	}

	public function product() {
		return $this->belongsTo(Product::class);
	}

	public function inventory() {
		return $this->hasMany(Inventory::class, 'product_id', 'product_id')
					->whereColumn('warehouse_location_id', 'outbound_products.warehouse_location_id');
	}

	public function warehouse_location() {
		return $this->belongsTo(WarehouseLocation::class);
	}
}
