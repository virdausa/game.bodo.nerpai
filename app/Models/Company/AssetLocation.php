<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetLocation extends Model
{
    use HasFactory;

    protected $table = 'asset_locations';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'type',
        'parent_id',
        'description'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(AssetLocation::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(AssetLocation::class, 'parent_id');
    }

    public function fixedAssets(): BelongsToMany
    {
        return $this->belongsToMany(FixedAsset::class);
    }
}
