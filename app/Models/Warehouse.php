<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
		'address',
		'status',
		'employee_id',
		'notes'
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

	public function locations(): HasMany
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
}
