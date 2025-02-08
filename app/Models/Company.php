<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'location',
        'database',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
