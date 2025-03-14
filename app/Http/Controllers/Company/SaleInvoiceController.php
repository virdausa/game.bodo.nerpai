<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Company\SaleInvoice;
use App\Models\Company\Finance\Receivable;
use App\Models\Company\Finance\JournalEntry;

class SaleInvoiceController extends Controller
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
        $sale_invoice = SaleInvoice::with('sale')->findOrFail($id);

        return view('company.sale_invoices.show', compact('sale_invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sale_invoice = SaleInvoice::with('sale')->findOrFail($id);

        return view('company.sale_invoices.edit', compact('sale_invoice'));
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

        $sale_invoice = SaleInvoice::findOrFail($id);
        $sale_invoice->update($validated);
        $sale_invoice->generateNumber();

        // update total amount
        $sale_invoice->total_amount = $sale_invoice->cost_products + 
                                        $sale_invoice->vat_input +
                                        $sale_invoice->cost_packing + 
                                        $sale_invoice->cost_insurance + 
                                        $sale_invoice->cost_freight;
        $sale_invoice->save();

        $sale = $sale_invoice->sale;
        return redirect()->route('sales.show', $sale->id)->with('success', "Invoice {$sale_invoice->number} updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sale_invoice = SaleInvoice::findOrFail($id);
        $sale_invoice->forceDelete();

        // back to url before
        return redirect()->to(url()->previous())->with('success', "Invoice {$sale_invoice->number} deleted successfully.");
    }



    public function handleAction(Request $request, $id, $action){
		$invoice = SaleInvoice::findOrFail($id);

		switch($action){
			case 'unpaid':
				$this->confirmInvoice($invoice);
				break;
			default:
				abort(404);
		}

		return redirect()->route('sale_invoices.show', $invoice->id)->with('success', "Invoice {$invoice->number} updated successfully.");
	} 

    
    public function confirmInvoice($invoice)
    {
        // Register to AR
        $this->addInvoicetoAR($invoice);
        $this->addInvoicetoJournal($invoice);

        $invoice->status = 'unpaid';
        $invoice->save();
    }


    public function addInvoicetoAR($invoice)
    {
        $receivable = Receivable::create([
            'sale_invoice_id' => $invoice->id,
            'customer_id' => $invoice->sale->customer_id,
            'total_amount' => $invoice->total_amount,
            'status' => 'unconfirmed',
        ]);
    }


    public function addInvoicetoJournal($invoice)
    {
        $employee = session('employee');

        $journal_entry = JournalEntry::create([
            'date' => date('Y-m-d'),
            'sale_invoice_id' => $invoice->id,
            'total_amount' => $invoice->total_amount,
            'description' => 'pendapatan dimuka',
            'type' => 'AR',
            'source_type' => 'SOI',
            'source_id' => $invoice->id,
            'created_by' => $employee->id,
        ]);

        $details = [
            // Debit Uang Muka
            [
                'journal_entry_id' => $journal_entry->id,
                'account_id' => 4,                          // piutang usaha, 4
                'debit' => $invoice->total_amount,
                'credit' => 0,
            ],

            // Kredit Hutang
            [
                'journal_entry_id' => $journal_entry->id,
                'account_id' => 24,                          // pendapatan diterima di muka, 24
                'debit' => 0,
                'credit' => $invoice->total_amount,
            ],
        ];

        $journal_entry->journal_entry_details()->createMany($details);

        // post journal to GL
        $journal_entry->postJournalEntrytoGeneralLedger();

        $journal_entry->generateNumber();
        $journal_entry->save();
    }
}
