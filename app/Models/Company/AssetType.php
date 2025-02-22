<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AssetType extends Model
{
    use HasFactory;

    protected $table = 'asset_types';
    protected $timestamps = true;

    protected $fillable = [
        'name',
        'description'
    ];

    public function fixedAssets(): BelongsToMany
    {
        return $this->belongsToMany(FixedAsset::class);
    }
}
