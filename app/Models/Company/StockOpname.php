<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockOpname extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stock_opnames';

    protected $timestamps = true;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'warehouse_location',
        'system_quantity',
        'physical_quantity',
        'adjustment_quantity',
        'adjustment_value',
        'notes',
        'date'
    ];

    protected $casts = [    
        'adjustment_value' => 'decimal:2',
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
