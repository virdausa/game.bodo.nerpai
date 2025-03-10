<?php

namespace App\Http\Controllers\Company\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Company\Finance\Payable;
use App\Models\Company\Finance\Payment;
use App\Models\Company\Finance\PaymentDetail;
use App\Models\Company\Supplier;

class PayableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // supplier yang punya payable
        $suppliers = Supplier::with('payables')->get();

        return view('company.finance.payables.index', compact('suppliers'));
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
        $supplier = Supplier::with('payables')->findOrFail($id);
        
        return view('company.finance.payables.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    public function handleAction(Request $request, $id, $action){
		$supplier = Supplier::findOrFail($id);

		switch($action){
			case 'PAYMENT_REQUEST':
				$this->makePaymentRequestforPayables($supplier);
				break;
			default:
				abort(404);
		}

		return redirect()->route('payables.show', $supplier->id)->with('success', "Payable {$supplier->name} updated successfully.");
	} 


    public function makePaymentRequestforPayables($supplier)
    {
        $unpaid_payables = $supplier->payables()->where('status', 'unconfirmed')->get();

        $payment = Payment::create([
            'date' => date('Y-m-d'),
            'type' => 'AP',
            'source_type' => 'SUP',
            'source_id' => $supplier->id,
            'status' => 'PENDING',
            'notes' => 'Payment Request',
            'total_amount' => $unpaid_payables->sum('total_amount'),
        ]);
        $payment->generateNumber();

        $payment_details = [];
        foreach ($unpaid_payables as $payable) {
            $invoice = $payable->purchase_invoice;

            $payment_details[] = [
                'payment_id' => $payment->id,
                'invoice_type' => 'POI',
                'invoice_id' => $invoice->id,
                'amount' => $invoice->total_amount,
                'balance' => 0,
            ];

            $payable->status = 'unpaid';
            $payable->save();
        }
        PaymentDetail::insert($payment_details);

        $payment->save();
    }
}
