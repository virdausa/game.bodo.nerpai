<?php

namespace App\Models\Store;

use App\Models\Shipment;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreInbound extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'store_inbounds';
    protected $timestamps = true;

    protected $fillable = [
        'store_id',
        'shipment_id',
        'store_employee_id',
        'notes',
        'status',
        'inbound_date'
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function storeEmployee(): BelongsTo
    {
        return $this->belongsTo(StoreEmployee::class);
    }

    public function storeInboundProducts(): HasMany
    {
        return $this->hasMany(StoreInboundProduct::class);
    }
}
