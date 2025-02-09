<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    protected $table = 'company_users';

    protected $fillable = [
        'user_id', 'user_type', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
