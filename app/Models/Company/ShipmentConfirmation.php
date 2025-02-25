<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Product;

class ShipmentConfirmation extends Model
{
    protected $table = 'shipment_confirmations';

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shipment_id',
        'employee_id',
        'consignee_type',
        'consignee_id',
        'consignee_signature',
        'received_time',
        'notes'
    ];

    protected $casts = [
        'received_time' => 'datetime',
    ];

    // Relationships
    public function products()
    {
        return $this->belongsToMany(Product::class, 'shipment_confirmation_products')
                    ->withPivot('quantity', 'condition', 'notes')
                    ->withTimestamps();
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function consignee(): MorphTo
    {
        return $this->morphTo();
    }
}
