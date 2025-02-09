<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;  // Pastikan Role diimport

class Employee extends Model
{
    use HasFactory;

    use HasRoles;
    protected $guarded = [];
    protected $guard_name = 'company';

    protected $fillable = [
        'company_user_id',
        'reg_date',
        'out_date',
        'status',
        'role_id',
    ];

    protected $casts = [
        'status' => 'boolean',
        'reg_date' => 'date',
        'out_date' => 'date',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(CompanyUser::class, 'company_user_id');
    }

    // Relasi ke Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');  // Menghubungkan dengan Role
    }

    // Mengakses permissions yang terkait dengan role
    public function permissions()
    {
        return $this->role->permissions;  // Mengakses permissions yang dimiliki oleh role
    }

    // Menambahkan metode untuk memeriksa permission langsung dari role
    public function can($permission)
    {
        return $this->permissions()->contains('name', $permission);  // Memeriksa apakah role memiliki permission
    }
}
