<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Courier extends Model
{
    protected $table = 'couriers';

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'contact_info',
        'website',
        'status',
        'notes'
    ];
}
