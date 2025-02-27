<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Company\PurchaseInvoice;

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $purchase_invoice = PurchaseInvoice::with('purchase')->findOrFail($id);

        return view('company.purchase_invoices.edit', compact('purchase_invoice'));
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
                                        $purchase_invoice->cost_packing + 
                                        $purchase_invoice->cost_insurance + 
                                        $purchase_invoice->cost_freight;
        $purchase_invoice->save();

        $purchase = $purchase_invoice->purchase;
        return redirect()->route('purchases.show', $purchase->id)->with('success', "Invoice {$purchase_invoice->invoice_number} updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchase_invoice = PurchaseInvoice::findOrFail($id);
        $purchase_invoice->forceDelete();

        // back to url before
        return redirect()->to(url()->previous())->with('success', "Invoice {$purchase_invoice->invoice_number} deleted successfully.");
    }
}
