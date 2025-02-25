<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inbound extends Model
{
    protected $table = 'inbounds';

    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'warehouse_id',
		'requested_quantities',
        'received_quantities',
        'status',
		'arrival_date',
        'verified_by',
        'notes',
    ];

    protected $casts = [
		'received_quantities' => 'array',
		'requested_quantities' => 'array', // Add this line
	];


    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_order_id');
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


}
