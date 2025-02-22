<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class FixedAsset extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fixed_assets';
    protected $timestamps = true;

    protected $fillable = [
        'asset_code',
        'asset_type_id',
        'asset_location_id',
        'name',
        'purchase_date',
        'purchase_value',
        'useful_life',
        'depreciation_method',
        'residual_value',
        'current_value',
        'status'
    ];

    protected $casts = [
        'purchase_value' => 'decimal:2',
        'useful_life' => 'decimal:2',
        'residual_value' => 'decimal:2',
        'current_value' => 'decimal:2',
    ];

    public function assetType(): HasOne
    {
        return $this->hasOne(AssetType::class);
    }

    public function assetLocation(): HasOne
    {
        return $this->hasOne(AssetLocation::class);
    }

    public function depreciationEntry(): HasOne
    {
        return $this->hasOne(DepreciationEntry::class);
    }

    public function assetMaintenance(): HasOne
    {
        return $this->hasOne(AssetMaintenance::class);
    }

    public function assetDisposal(): HasOne
    {
        return $this->hasOne(AssetDisposal::class);
    }
}
