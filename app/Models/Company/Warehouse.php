<?php

namespace App\Models\Company;

use App\Models\Store\StoreWarehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
	use HasFactory;

	protected $fillable = [
		'code',
		'name',
		'address',
		'status',
		'employee_id',
		'notes',
		'valuation_method',
	];

	protected $casts = [
		'address' => 'array'
	];

	public function products(): BelongsToMany
	{
		return $this->belongsToMany(Product::class, 'inventory')
			->withPivot('quantity')
			->withTimestamps();
	}

	public function warehouse_locations(): HasMany
	{
		return $this->hasMany(WarehouseLocation::class);
	}

	public function employee()
	{
		return $this->belongsTo(Employee::class, 'employee_id');
	}


	public function scopeActive($query)
	{
		return $query->where('status', 'active');
	}

	public function scopeInactive($query)
	{
		return $query->where('status', 'inactive');
	}

	public function storeWarehouses(): HasMany
    {
        return $this->hasMany(StoreWarehouse::class);
    }
}
