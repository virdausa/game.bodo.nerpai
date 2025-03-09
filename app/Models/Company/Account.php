<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'accounts';

    protected $fillable = [
        'code',
        'name',
        'type_id',
        'parent_id',
        'status',
        'balance',
    ];

    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function account_type()
    {
        return $this->belongsTo(AccountType::class, 'type_id');
    }
}
