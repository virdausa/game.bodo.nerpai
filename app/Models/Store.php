<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
}
