<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class StorePermission extends Model
{
    protected $table = 'store_permissions';

    protected $fillable = [
        'name'
    ];

    public $timestamps = true;

    public function roles()
    {
        return $this->belongsToMany(
            StoreRole::class, 
            'store_role_has_permissions', 
            'store_permission_id', 
            'store_role_id'
        );
    }
}
