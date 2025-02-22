<?php

namespace App\Models\Company;

use App\Models\JournalEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetDisposal extends Model
{
    use HasFactory;

    protected $table = 'asset_disposals';
    protected $timestamps = true;

    protected $fillable = [
        'fixed_asset_id',
        'journal_entry_id',
        // 'vendor_id',
        'disposal_date',
        'disposal_value',
        'book_value',
        'gain_or_loss',
        'status',
        'notes'
    ];

    protected $casts = [
        'disposal_value' => 'decimal:2',
        'book_value' => 'decimal:2',
        'gain_or_loss' => 'decimal:2',
    ];

    public function fixedAsset(): BelongsTo
    {
        return $this->belongsTo(FixedAsset::class);
    }

    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }

    // public function vendor(): BelongsTo
    // {
    //     return $this->belongsTo(Vendor::class);
    // }
}
