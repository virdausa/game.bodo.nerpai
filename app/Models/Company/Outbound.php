<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Company\Shipment;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Employee;

class Outbound extends Model
{
    protected $table = 'outbounds';

    use HasFactory;

    protected $fillable = [
        'number',
        'warehouse_id',
        'source_type',
        'source_id',
        'employee_id',
        'date',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function generateNumber()
    {
        $this->number = 'OUTB_' . $this->date?->format('Y-m-d') . '_' . $this->id;
        return $this->number;
    }


    public function source()
    {
        return $this->morphTo();
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function outbound_products()
    {
        return $this->hasMany(OutboundProduct::class);
    }

    public function shipments() :hasMany
    {
        return $this->hasMany(Shipment::class, 'transaction_id', 'id')
                    ->where('transaction_type', 'OUTB');
    }
}
