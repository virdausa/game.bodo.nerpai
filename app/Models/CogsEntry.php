<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CogsEntry extends Model
{
    use HasFactory;

    protected $table = 'cogs_entries';
    protected $timestamps = true;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'cost_per_unit',
        'total_cost',
        'date',
        'notes'
    ];

    protected $casts = [
        'cost_per_unit' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
