<?php

namespace App\Models\Space;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\CompanyUser;

class Company extends Model
{
    protected $table = 'companies';
    
    protected $connection = 'space';

    protected $fillable = [
        'id',
        'code',
        'name',
        'location',
        'database',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'companies_users', 'company_id', 'user_id')
                    ->withPivot('status'); 
    }

    public function companyusers(){
        return $this->hasMany(CompanyUser::class);
    }
}
