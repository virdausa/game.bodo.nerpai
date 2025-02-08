<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $connection = 'mysql';

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

    public function users()
    {
        return $this->hasMany(User::class, 'companies_users', 'company_id', 'user_id')
                    ->withPivot('status');  
    }
}
