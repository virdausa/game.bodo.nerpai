<?php

namespace App\Models\Company;

use App\Models\JournalEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMaintenance extends Model
{
    use HasFactory;

    protected $table = 'asset_maintenances';
    protected $timestamps = true;

    protected $fillable = [
        'fixed_asset_id',
        'journal_entry_id',
        // 'vendor_id',
        'maintenance_date',
        'description',
        'cost',
        'status',
        'notes'
    ];

    protected $casts = [
        'cost' => 'decimal:2',
    ];

    public function fixedAsset()
    {
        return $this->belongsTo(FixedAsset::class);
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    // public function vendor()
    // {
    //     return $this->belongsTo(Vendor::class);
    // }
}
