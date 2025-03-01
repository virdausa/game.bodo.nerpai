<?php

namespace App\Models\Store;

use App\Models\Company\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StorePos extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'store_pos';
    protected $timestamps = true;

    protected $fillable = [
        'store_id',
        'store_customer_id',
        'store_employee_id',
        'pos_date',
        'total_amount',
        'tax_amount',
        'payment_method',
        'status',
        'notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function storeCustomer(): BelongsTo
    {
        return $this->belongsTo(StoreCustomer::class);
    }

    public function storeEmployee(): BelongsTo
    {
        return $this->belongsTo(StoreEmployee::class);
    }

    public function storePosProducts(): HasMany
    {
        return $this->hasMany(StorePosProduct::class);
    }
}
