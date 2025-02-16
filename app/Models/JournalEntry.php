<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
}
