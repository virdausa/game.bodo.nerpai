<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Company\Product;
use App\Models\Employee;

use App\Models\Company\Warehouse;
use App\Models\Company\Customer;

class Sale extends Model
{
    protected $table = 'sales';

    use HasFactory;

    protected $fillable = [
        'number',
        'date',
        'employee_id',
        'consignee_type',
        'consignee_id',
        'warehouse_id',
        'total_amount',
        'status',
        'customer_notes',
        'admin_notes',
        'courier_id',
        'estimated_shipping_fee',
        'shipping_fee_discount',
    ];

    public function generateSoNumber(): string
    {
        $this->number = 'SO_' . $this->date . '_' . $this->id;
        return $this->number;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'sales_products')
            ->withPivot('id', 'quantity', 'price', 'total_cost', 'notes')
            ->withTimestamps();
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function consignee()
    {
        return $this->morphTo();
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function invoices()
    {
        return $this->hasMany(SaleInvoice::class);
    }


    public function shipments(): HasMany
    {
        // shipment with type = "PO" and id = purchase_id
        return $this->hasMany(Shipment::class, 'transaction_id', 'id')
                    ->where('transaction_type', 'SO');
    }


    public function outbounds(): HasMany
    {
        return $this->hasMany(Outbound::class, 'source_id', 'id')
                    ->where('source_type', 'SO');
    }
}
