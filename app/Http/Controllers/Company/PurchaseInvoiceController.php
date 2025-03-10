<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Company\PurchaseInvoice;
use App\Models\Company\Finance\Payable;
use App\Models\Company\JournalEntry;
use App\Models\Company\JournalEntryDetail;
use App\Models\Company\Account;

class PurchaseInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchase_invoice = PurchaseInvoice::with('purchase')->findOrFail($id);

        return view('company.finance.purchase_invoices.show', compact('purchase_invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $purchase_invoice = PurchaseInvoice::with('purchase')->findOrFail($id);

        return view('company.finance.purchase_invoices.edit', compact('purchase_invoice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'due_date' => 'nullable|date',
            'cost_products' => 'required|numeric',
            'vat_input' => 'required|numeric',
            'cost_packing' => 'required|numeric',
            'cost_insurance' => 'required|numeric',
            'cost_freight' => 'required|numeric',
            'status' => 'required|string|max:255',
            'notes' => 'nullable|string|max:255',
        ]);

        $purchase_invoice = PurchaseInvoice::findOrFail($id);
        $purchase_invoice->update($validated);
        $purchase_invoice->generateInvoiceNumber();

        // update total amount
        $purchase_invoice->total_amount = $purchase_invoice->cost_products + 
                                        $purchase_invoice->vat_input +
                                        $purchase_invoice->cost_packing + 
                                        $purchase_invoice->cost_insurance + 
                                        $purchase_invoice->cost_freight;
        $purchase_invoice->save();

        $purchase = $purchase_invoice->purchase;
        return redirect()->route('purchases.show', $purchase->id)->with('success', "Invoice {$purchase_invoice->number} updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchase_invoice = PurchaseInvoice::findOrFail($id);
        $purchase_invoice->forceDelete();

        // back to url before
        return redirect()->to(url()->previous())->with('success', "Invoice {$purchase_invoice->number} deleted successfully.");
    }


    public function handleAction(Request $request, $id, $action){
		$invoice = PurchaseInvoice::findOrFail($id);

		switch($action){
			case 'unpaid':
				$this->confirmInvoice($invoice);
				break;
			default:
				abort(404);
		}

		return redirect()->route('purchase_invoices.show', $invoice->id)->with('success', "Invoice {$invoice->number} updated successfully.");
	} 

    
    public function confirmInvoice($invoice)
    {
        // Register to AP
        $this->addInvoicetoAP($invoice);
        $this->addInvoicetoJournal($invoice);

        $invoice->status = 'unpaid';
        $invoice->save();
    }


    public function addInvoicetoAP($invoice)
    {
        $payable = Payable::create([
            'purchase_invoice_id' => $invoice->id,
            'supplier_id' => $invoice->purchase->supplier_id,
            'total_amount' => $invoice->total_amount,
            'status' => 'unconfirmed',
        ]);
    }


    public function addInvoicetoJournal($invoice)
    {
        $employee = session('employee');

        $journal_entry = JournalEntry::create([
            'date' => date('Y-m-d'),
            'purchase_invoice_id' => $invoice->id,
            'total_amount' => $invoice->total_amount,
            'description' => 'uang muka',
            'type' => 'AP',
            'source_type' => 'POI',
            'source_id' => $invoice->id,
            'created_by' => $employee->id,
        ]);

        $details = [
            // Debit Uang Muka
            [
                'journal_entry_id' => $journal_entry->id,
                'account_id' => 7,                          // uang muka, 7
                'debit' => $invoice->total_amount,
                'credit' => 0,
            ],

            // Kredit Hutang
            [
                'journal_entry_id' => $journal_entry->id,
                'account_id' => 22,                          // hutang usaha, 22
                'debit' => 0,
                'credit' => $invoice->total_amount,
            ],
        ];

        JournalEntryDetail::insert($details);

        $journal_entry->generateNumber();
        $journal_entry->save();

        $this->postJournalEntrytoGeneralLedger($journal_entry->id);
    }

    public function postJournalEntrytoGeneralLedger($journal_entry_id)
    {
        $journal_entry = JournalEntry::with('journal_entry_details')->findOrFail($journal_entry_id);

        foreach ($journal_entry->journal_entry_details as $detail) {
            $account = Account::findOrFail($detail->account_id);

            if($account){
                $account->balance += $detail->debit * $account->account_type->debit;
                $account->balance += $detail->credit * $account->account_type->credit;
                $account->save();
            }
        }
    }
}
