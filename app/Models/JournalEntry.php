<?php

namespace App\Models;

use App\Models\Company\AssetDisposal;
use App\Models\Company\AssetMaintenance;
use App\Models\Company\DepreciationEntry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalEntry extends Model
{
    use SoftDeletes;

    protected $table = 'journal_entries';
    protected $timestamps = true;

    protected $fillable = [
        'no',
        'date',
        'type',
        'description',
        'total',
        'created_by',
    ];

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
