<?php

namespace App\Models\Space;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'persons';

    protected $connection = 'space';

    protected $fillable = [
        'name',
        'number',
        'full_name',
        'birth_date',
        'death_date',
        'sex',
        'address',  
        'phone_number',    
        'status', 
    ];
}
