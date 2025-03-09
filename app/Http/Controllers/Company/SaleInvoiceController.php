<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Company\SaleInvoice;

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
        //
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
}
