<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Employee;
use App\Models\Company\Product;
use App\Models\Company\Supplier;
use App\Models\Company\Warehouse;
use App\Models\Company\PurchaseInvoice;

class Purchase extends Model
{
    protected $table = 'purchases';

    use HasFactory;

    protected $fillable = [
        'po_number',
        'po_date',
        'supplier_id',
        'warehouse_id',
        'employee_id',
        'total_amount',
        'status',
        'admin_notes',
        'supplier_notes'
    ];

    protected $casts = [
        'po_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'purchase_products')
            ->withPivot('quantity', 'buying_price', 'total_cost')
            ->withTimestamps();
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function shipments(): HasMany
    {
        // shipment with type = "PO" and id = purchase_id
        return $this->hasMany(Shipment::class, 'transaction_id', 'id')
                    ->where('transaction_type', 'PO');
    }

    public function purchase_invoices(): HasMany
    {
        return $this->hasMany(PurchaseInvoice::class);
    }

    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function generatePoNumber(): string
    {
        $this->po_number = 'PO_' . ($this->po_date)->format('Y-m-d') . '_' . $this->id;
        return $this->po_number;
    }
}
