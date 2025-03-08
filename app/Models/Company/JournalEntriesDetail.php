<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalEntriesDetail extends Model
{
    use SoftDeletes;

    protected $table = 'journal_entries_details';
    protected $timestamps = true;

    protected $fillable = [
        'journal_entry_id',
        'account_id',
        'debit',
        'credit',
        'notes'
    ];
}
