<?php

namespace App\Models\Company;

use App\Models\Company\AssetDisposal;
use App\Models\Company\AssetMaintenance;
use App\Models\Company\DepreciationEntry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Company\JournalEntryDetail;

class JournalEntry extends Model
{
    use SoftDeletes;

    protected $table = 'journal_entries';
    public $timestamps = true;

    protected $fillable = [
        'number',
        'date',
        'type',
        'source_type',
        'source_id',
        'description',
        'total',
        'created_by',
    ];

    public function generateNumber()
    {
        $this->number = $this->type . '_' . $this->id;
        return $this->number;
    }


    public function journal_entry_details(): HasMany
    {
        return $this->hasMany(JournalEntryDetail::class);
    }

    public function source()
    {
        return $this->morphTo();
    }

    public function created_by()
    {
        return $this->belongsTo(Employee::class, 'created_by');
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
