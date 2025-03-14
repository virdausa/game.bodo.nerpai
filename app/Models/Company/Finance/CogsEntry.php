<?php

namespace App\Models\Company\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CogsEntry extends Model
{
    use HasFactory;

    protected $table = 'cogs_entries';

    public $timestamps = true;

    protected $fillable = [
        'source_type',
        'source_id',
        'product_id',
        'quantity',
        'cost_per_unit',
        'total_cost',
        'date',
        'notes',
    ];

    protected $casts = [
        'cost_per_unit' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function source()
    {
        return $this->morphTo();
    }
}
