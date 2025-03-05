<?php

namespace App\Models\Store;

use App\Models\Company\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\Product;

class StoreRestock extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'store_restocks';
    
    public $timestamps = true;

    protected $fillable = [
        'number',
        'warehouse_id',
        'store_id',
        'store_employee_id',
        'restock_date',
        'total_amount',
        'status',
        'admin_notes',
        'team_notes'
    ];


    public function generateNumber()
    {
        $this->number = 'STR_' . $this->restock_date . '_' . $this->id;
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function store_employee(): BelongsTo
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
