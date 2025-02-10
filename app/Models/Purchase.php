<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_date',
        'supplier_id',
        'warehouse_id',
        'employee_id',
        'total_amount',
        'status',
        'admin_notes',
        'supplier_notes'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'purchase_product')
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

    public function inboundRequests(): HasMany
    {
        return $this->hasMany(InboundRequest::class, 'purchase_order_id');
    }

    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
