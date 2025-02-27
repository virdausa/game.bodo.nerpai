<?php

namespace App\Models\Company;

use App\Models\Store\StoreInbound;
use App\Models\Store\StoreOutbound;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    protected $table = 'shipments';

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shipper_type',
        'shipper_id',
        'consignee_type',
        'consignee_id',
        'origin_address',
        'destination_address',
        'transaction_type',
        'transaction_id',
        'courier_id',
        'ship_date',
        'tracking_number',
        'shipping_fee',
        'payment_rules',
        'notes',
        'status'
    ];

    protected $casts = [
        'origin_address' => 'array',
        'destination_address' => 'array',
        'ship_date' => 'date',
    ];

    // Polymorphic relationships
    public function shipper(): MorphTo
    {
        return $this->morphTo();
    }

    public function consignee(): MorphTo
    {
        return $this->morphTo();
    }


    public function transaction(): MorphTo
    {
        return $this->morphTo();
    }

    public function storeInbounds(): HasMany
    {
        return $this->hasMany(StoreInbound::class);
    }

    public function storeOutbounds(): HasMany
    {
        return $this->hasMany(StoreOutbound::class);
    }

    public function generateShipmentNumber(): string
    {
        $this->shipment_number = 'SHP_' . $this->id . '_' . $this->transaction_type . '_' . $this->transaction_id;
        return 'SHIP-' . $this->id;
    }
}
