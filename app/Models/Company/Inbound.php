<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Employee;
use App\Models\Company\Product;
use App\Models\Company\ShipmentConfirmation;
use App\Models\Company\Warehouse;
use App\Models\Company\InboundProducts;

class Inbound extends Model
{
    protected $table = 'inbounds';

    use HasFactory;

    protected $fillable = [
        'warehouse_id',
		'shipment_confirmation_id',
        'employee_id',
		'inbound_date',
        'status',
        'notes',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shipment_confirmation()
    {
        return $this->belongsTo(ShipmentConfirmation::class);
    }

    
    public function inbound_products()
    {
        return $this->hasMany(InboundProducts::class);
    }
}
