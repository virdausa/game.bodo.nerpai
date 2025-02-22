<?php

namespace App\Models\Company;

use App\Models\JournalEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepreciationEntry extends Model
{
    use HasFactory;

    protected $table = 'depreciation_entries';
    protected $timestamps = true;

    protected $fillable = [
        'fixed_asset_id',
        'journal_entry_id',
        'depreciation_date',
        'depreciation_amount',
        'accumulated_depreciation',
        'depreciation_method',
        'book_value',
        'notes'
    ];

    protected $casts = [
        'depreciation_amount' => 'decimal:2',
        'accumulated_depreciation' => 'decimal:2',
        'book_value' => 'decimal:2',
    ];

    public function fixedAsset(): BelongsTo
    {
        return $this->belongsTo(FixedAsset::class);
    }

    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }
}
