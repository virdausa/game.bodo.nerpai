<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    protected $table = 'company_users';

    protected $fillable = [
        'user_id', 'user_type', 'status',
    ];
}
