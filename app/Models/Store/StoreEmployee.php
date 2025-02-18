<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Employee;
use App\Models\Store;

class StoreEmployee extends Model
{
    use softDeletes;

    protected $table = 'store_employees';

    public $timestamps = true;

    protected $fillable = [
        'store_id',
        'employee_id',
        'store_role_id',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function store_role()
    {
        return $this->belongsTo(StoreRole::class);
    }
}
