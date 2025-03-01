<?php

namespace App\Models\Store;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreRestock extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'store_restocks';
    
    public $timestamps = true;

    protected $fillable = [
        'number',
        'store_id',
        'store_employee_id',
        'restock_date',
        'total_amount',
        'status',
        'admin_notes',
        'team_notes'
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function storeEmployee(): BelongsTo
    {
        return $this->belongsTo(StoreEmployee::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'store_restock_products')
                    ->withPivot('id', 'quantity', 'cost_per_unit', 'total_cost', 'notes')
                    ->withTimestamps();
    }
}
