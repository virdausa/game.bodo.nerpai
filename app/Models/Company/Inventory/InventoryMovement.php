<?php

namespace App\Models\Company\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Company\Product;
use App\Models\Company\Inventory\Inventory;
use App\Models\Company\Employee;
use App\Models\Company\Warehouse;
use App\Models\Company\WarehouseLocation;

class InventoryMovement extends Model
{
    use HasFactory;
	
	protected $table = 'inventory_movements';
	
    public $timestamps = false;

    protected $fillable = [
		'product_id', 
        'warehouse_id', 
        'quantity', 
        'cost_per_unit',
        'source_type',
        'source_id', 
		'warehouse_location_id',
        'employee_id',
        'notes',
        'time',
	];


    // relations

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
	
	public function warehouse_location()
	{
		return $this->belongsTo(WarehouseLocation::class);
	}

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }



    // functions
    public function postMovement()
    {
        $inventory = Inventory::updateOrCreate(
            [
                'product_id' => $this->product_id,
                'warehouse_id' => $this->warehouse_id,
                'warehouse_location_id' => $this->warehouse_location_id,
            ],
            [
                'cost_per_unit' => $this->cost_per_unit,
            ]
        );

        if($this->source_type == 'INB') {
            $inventory->increment('quantity', $this->quantity);
        } else if($this->source_type == 'OUTB') {
            $inventory->decrement('quantity', $this->quantity);
        }

        $inventory->save();
    }
}
