<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
	protected $table = 'warehouse_locations';

	protected $fillable = [
		'warehouse_id',
		'room',
		'rack'
	];

	public function warehouse()
	{
		return $this->belongsTo(Warehouse::class);
	}

	public function inventory()
	{
		return $this->hasMany(Inventory::class);
	}

	public function locations()
	{
		return $this->hasMany(OutboundRequestLocation::class);
	}
}
