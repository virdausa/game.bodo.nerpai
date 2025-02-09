<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class CompanyUser extends Model
{
    use HasRoles;

    protected $table = 'company_users';

    protected $fillable = [
        'user_id', 'user_type', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'company_user_id');
    }

    public function canEmployee($permission)
    {
        return $this->employee->can($permission);
    }
}
