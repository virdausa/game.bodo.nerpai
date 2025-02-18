<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class StoreRole extends Model
{
    protected $table = 'store_roles';

    protected $fillable = [
        'name',
    ];

    public $timestamps = true;

    public function permissions()
    {
        return $this->belongsToMany(
            StorePermission::class, 
            'store_role_has_permissions', 
            'store_role_id', 
            'store_permission_id'
        );
    }
}
