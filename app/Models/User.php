<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    protected $table = 'users';

    protected $connection = 'mysql';

    use HasFactory, Notifiable, HasRoles;

    // Kolom dan relasi yang ada di User tidak berubah
    protected $fillable = [
        'name',
        'email',
        'password',
        'tgl_lahir',  
        'alamat',    
        'no_hp',     
        'role',      
        'tgl_keluar', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'tgl_lahir' => 'date',    
            'tgl_keluar' => 'date',   
        ];
    }

    // Relasi ke Company
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'companies_users', 'user_id', 'company_id')
                    ->withPivot(('status'));
    }

    public function approvedCompanies(){
        return $this->companies()
                    ->wherePivot('status', 'approved');
    }



    // Relasi ke Employee
    // public function employee()
    // {
    //     return $this->hasOne(Employee::class);
    // }

    // Memeriksa permission berdasarkan Employee
    // public function canEmployee($permission)
    // {
    //     return $this->employee->can($permission);
    // }
}
