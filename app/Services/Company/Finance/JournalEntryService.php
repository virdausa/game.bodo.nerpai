<?php

namespace App\Services\Company\Finance;

use App\Models\Company\Finance\JournalEntry;
use App\Models\Company\Finance\JournalEntryDetail;

class JournalEntryService
{
    public function addJournalEntry($data, $details = [])
    {
        $employee = session('employee');

        $journal_entry = JournalEntry::create([
            'created_by' => $data['created_by'] ?? $employee->id,
            'source_type' => $data['source_type'] ?? null,
            'source_id' => $data['source_id'] ?? null,
            'date' => $data['date'] ?? date('Y-m-d'),
            'type' => $data['type'] ?? 'MNL',
            'description' => $data['description'] ?? null,
            'total' => $data['total'] ?? 0,
        ]);

        $journal_details = [];
        foreach($details as $detail) {
            $journal_details[] = [
                'journal_entry_id' => $journal_entry->id,
                'account_id' => $detail['account_id'],
                'debit' => $detail['debit'] ?? 0,
                'credit' => $detail['credit'] ?? 0,
                'notes' => $detail['notes'] ?? null,
            ];
        }

        $journal_entry->journal_entry_details()->createMany($journal_details);

        // post journal to GL
        $journal_entry->postJournalEntrytoGeneralLedger();

        $journal_entry->generateNumber();
        $journal_entry->save();

        return $journal_entry;
    }
}