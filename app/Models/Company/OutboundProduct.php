<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

use App\Models\Product;
use App\Models\Company\WarehouseLocation;
use App\Models\Company\Outbound;

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

	public function warehouse_location() {
		return $this->belongsTo(WarehouseLocation::class);
	}
}
