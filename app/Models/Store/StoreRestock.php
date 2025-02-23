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
    protected $timestamps = true;

    protected $fillable = [
        'store_id',
        'store_employee_id',
        'restock_date',
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
}
