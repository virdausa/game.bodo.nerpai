<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $connection = 'tenant';

    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'email',
        'phone_number',
        'status',
        'notes',
    ];

    public function getSuppliers(): Collection
    {
        return $this->all();
    }

    public function getSupplier($id): Supplier
    {
        return $this->findOrFail($id);
    }

    public function createSupplier(array $data): Supplier
    {
        return $this->create($data);
    }

    public function updateSupplier(array $data, Supplier $supplier): Supplier
    {
        $supplier->update($data);
        return $supplier;
    }

    public function deleteSupplier(Supplier $supplier): void
    {
        $supplier->delete();
    }

    public function getPurchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
}
