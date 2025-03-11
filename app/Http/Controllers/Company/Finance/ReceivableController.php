<?php

namespace App\Http\Controllers\Company\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Company\Customer;
use App\Models\Company\Finance\Receivable;
use App\Models\Company\Finance\Payment;
use App\Models\Company\Finance\PaymentDetail;

class ReceivableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::with('receivables')->get();

        return view('company.finance.receivables.index', compact('customers'));
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
        $customer = Customer::with('receivables')->findOrFail($id);
   
        return view('company.finance.receivables.show', compact('customer'));
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
		$customer = Customer::findOrFail($id);

		switch($action){
			case 'PAYMENT_REQUEST':
				$this->makePaymentRequestforReceivable($customer);
				break;
			default:
				abort(404);
		}

		return redirect()->route('receivables.show', $customer->id)->with('success', "Receivable {$customer->name} updated successfully.");
	} 


    public function makePaymentRequestforReceivable($customer)
    {
        $unpaid_receivables = $customer->receivables()->where('status', 'unconfirmed')->get();

        $payment = Payment::create([
            'date' => date('Y-m-d'),
            'type' => 'AR',
            'source_type' => 'CUST',
            'source_id' => $customer->id,
            'status' => 'PYM_PENDING',
            'notes' => 'Payment Request',
            'total_amount' => $unpaid_receivables->sum('total_amount'),
        ]);
        $payment->generateNumber();

        $payment_details = [];
        foreach ($unpaid_receivables as $receivable) {
            $invoice = $receivable->invoice;

            $payment_details[] = [
                'payment_id' => $payment->id,
                'invoice_type' => 'SOI',
                'invoice_id' => $invoice->id,
                'amount' => $invoice->total_amount,
                'balance' => 0,
            ];

            $receivable->status = 'unpaid';
            $receivable->save();
        }
        PaymentDetail::insert($payment_details);

        $payment->save();
    }
}
