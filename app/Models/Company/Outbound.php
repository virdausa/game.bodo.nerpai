<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outbound extends Model
{
    protected $table = 'outbounds';

    use HasFactory;

    protected $fillable = [
        'sales_order_id', 
        'warehouse_id', 
        'requested_quantities', 
		'received_quantities',
        'status', 
        'verified_by', 
        'notes', 
        'packing_fee', 
        'shipping_fee', 
        'tracking_number', 
        'real_volume', 
        'real_weight',
		'expedition_id',
		'real_shipping_fee',
    ];

    protected $casts = [
		'received_quantities' => 'array',
		'requested_quantities' => 'array', // Add this line
	];

    public function sales()
    {
        return $this->belongsTo(Sale::class, 'sales_order_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
	
	public function productQuantities()
	{
		$requestedQuantities = collect($this->requested_quantities); // Cast to Collection
		$receivedQuantities = collect($this->received_quantities); // Cast to Collection

		return $requestedQuantities->mapWithKeys(function ($quantity, $productId) use ($receivedQuantities) {
			return [$productId => $quantity - ($receivedQuantities->get($productId, 0))];
		});
	}
	
	public function expedition()
	{
		return $this->belongsTo(Expedition::class);
	}
	
	
	public function locations()
	{
		return $this->hasMany(OutboundRequestLocation::class);
	}
}
