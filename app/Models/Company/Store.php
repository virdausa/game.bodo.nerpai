<?php

namespace App\Models\Company;

use App\Models\Store\StoreCustomer;
use App\Models\Store\StoreInbound;
use App\Models\Store\StoreInventory;
use App\Models\Store\StoreOutbound;
use App\Models\Store\StorePos;
use App\Models\Store\StoreProduct;
use App\Models\Store\StoreRestock;
use App\Models\Store\StoreWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use softDeletes;

    protected $table = 'stores';

    public $timestamps = true;

    protected $fillable = [
        'code',
        'name',
        'address',
        'status',
        'manager',
        'notes',
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'store_employees', 'store_id', 'employee_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function storeRestocks(): HasMany
    {
        return $this->hasMany(StoreRestock::class);
    }

    public function storeCustomers(): HasMany
    {
        return $this->hasMany(StoreCustomer::class);
    }

    public function storePos(): HasMany
    {
        return $this->hasMany(StorePos::class);
    }

    public function storeProducts(): HasMany
    {
        return $this->hasMany(StoreProduct::class);
    }

    public function storeWarehouses(): HasMany
    {
        return $this->hasMany(StoreWarehouse::class);
    }

    public function storeInventories(): HasMany
    {
        return $this->hasMany(StoreInventory::class);
    }

    public function storeInbounds(): HasMany
    {
        return $this->hasMany(StoreInbound::class);
    }

    public function storeOutbounds(): HasMany
    {
        return $this->hasMany(StoreOutbound::class);
    }
}
