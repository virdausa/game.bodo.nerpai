<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Product;
use App\Models\Company\Warehouse;
use App\Models\Customer;
use App\Models\Employee;

class Sale extends Model
{
    protected $table = 'sales';

    use HasFactory;

    protected $fillable = [
        'so_number',
        'sale_date',
        'employee_id',
        'customer_id',
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
        $this->so_number = 'SO-' . $this->sale_date . '-' . $this->id;
        return $this->so_number;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'sales_products')
            ->withPivot('quantity', 'price', 'total_cost', 'notes')
            ->withTimestamps();
    }

    // Relationship with Product
    public function salesProducts()
    {
        return $this->belongsTo(SalesProduct::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function productQuantities()
    {
        return $this->products->mapWithKeys(function ($product) {
            return [$product->id => $product->pivot->quantity];
        });
    }


    // Get Status Name (optional helper method for status display)
    public function getStatusLabelAttribute()
    {
        return ucfirst(strtolower(str_replace('_', ' ', $this->status)));
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
