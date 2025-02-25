<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryHistory extends Model
{
    use HasFactory;
	
	protected $table = 'inventory_movements';
	
    protected $fillable = [
		'product_id', 'warehouse_id', 'quantity', 'transaction_type', 
		'location_id',	'notes'
	];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
	
	public function location()
	{
		return $this->belongsTo(WarehouseLocation::class);
	}

}
