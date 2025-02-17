<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreEmployee extends Model
{
    use softDeletes;

    protected $table = 'store_employees';

    public $timestamps = true;

    protected $fillable = [
        'store_id',
        'employee_id',
        'role',
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
}
