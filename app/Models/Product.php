<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
	use HasFactory;

	protected $fillable = [
		'sku',
		'name',
		'price',
		'weight',
		'dimension',
		'status',
		'notes'
	];

	protected $casts = [
		'dimension' => 'array',
	];

	public function getProducts(): Collection
	{
		return $this->all();
	}

	public function getProduct(String $id, $with = []): Product
	{
		$query = $this->newQuery();

		if (!empty($with)) {
			$query->with($with);
		}

		return $query->findOrFail($id);
	}

	public function createProduct(array $data): Product
	{
		return $this->create($data);
	}

	public function updateProduct(Product $product, array $data): Product
	{
		$product->update($data);
		return $product;
	}

	public function deleteProduct(Product $product): void
	{
		$product->delete();
	}

	public function purchases(): BelongsToMany
	{
		return $this->belongsToMany(Purchase::class, 'purchase_product')
			->withPivot('quantity', 'buying_price', 'total_cost')
			->withTimestamps();
	}

	public function warehouses(): BelongsToMany
	{
		return $this->belongsToMany(Warehouse::class, 'inventory')
			->withPivot('quantity')
			->withTimestamps();
	}

	public function salesProducts(): HasMany
	{
		return $this->hasMany(SalesProduct::class);
	}

	public function sales(): BelongsToMany
	{
		return $this->belongsToMany(Sale::class, 'sales_products')
			->withPivot('quantity', 'price', 'note')
			->withTimestamps();
	}
}
