<?php

namespace App\Http\Controllers\Company\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Company\Finance\Payment;
use App\Models\Company\Finance\PaymentDetail;
use App\Models\Company\JournalEntry;
use App\Models\Company\Account;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::all();

        return view('company.finance.payments.index', compact('payments'));
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
        $payment = Payment::with('payment_details')->findOrFail($id);
        $payment_methods = Account::where('type_id', 1)->get();         // Kas & Bank

        return view('company.finance.payments.show', compact('payment', 'payment_methods'));
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
		$payment = Payment::findOrFail($id);

		switch($action){
			case 'PAYMENT_PROCESS':
				$this->paymentProcess($payment);
				break;
            case 'PAYMENT_PAID':
                $this->paymentPaid($request, $payment);
                break;
			default:
				abort(404);
		}

		return redirect()->route('payments.show', $payment->id)->with('success', "Payment {$payment->number} updated successfully.");
	} 


    public function paymentProcess($payment){
        $payment->status = 'PYM_PROCESS';
        $payment->save();
    }


    public function paymentPaid(Request $request, $payment){
        $validated = $request->validate([
            'payment_method' => 'required|integer',
            'payment_proof' => 'nullable',
        ]);
        
        $payment->payment_method = $validated['payment_method'];

        $this->addPaymenttoJournal($payment);

        $payment->status = 'PYM_PAID';
        $payment->save();

        // update payment details
        foreach ($payment->payment_details as $detail) {
            $detail->invoice->status = 'paid';
            $detail->invoice->save();

            $detail->balance = $detail->amount;
            $detail->save();

            if($payment->type == 'AP'){
                $detail->invoice->payable->status = 'paid';
                $detail->invoice->payable->balance += $detail->amount;
                $detail->invoice->payable->save();
            } else if($payment->type == 'AR'){
                $detail->invoice->receivable->status = 'paid';
                $detail->invoice->receivable->balance += $detail->amount;
                $detail->invoice->receivable->save();
            }
        }
    }


    public function addPaymenttoJournal($payment)
    {
        $employee = session('employee');
        $journal_entry = JournalEntry::create([
            'date' => date('Y-m-d'),
            'description' => 'payment ' . $payment->number,
            'type' => 'PYM',
            'source_type' => 'PYM',
            'source_id' => $payment->id,
            'created_by' => $employee->id,
            'total' => $payment->total_amount,
        ]);

        // journal details
        $details = [];
        switch($payment->type){
            case 'AP':
                $details = [
                    // Debit Hutang Usaha
                    [
                        'journal_entry_id' => $journal_entry->id,
                        'account_id' => 22,                          // uang muka, 7
                        'debit' => $payment->total_amount,
                        'credit' => 0,
                    ],
        
                    // Kredit Kas & Bank
                    [
                        'journal_entry_id' => $journal_entry->id,
                        'account_id' => $payment->payment_method,     // Payment method
                        'debit' => 0,
                        'credit' => $payment->total_amount,
                    ],
                ];
                break;
            case 'AR':
                $details = [
                    // Debit Kas & Bank
                    [
                        'journal_entry_id' => $journal_entry->id,
                        'account_id' => $payment->payment_method,     // payment method
                        'debit' => $payment->total_amount,
                        'credit' => 0,
                    ],
        
                    // Kredit Piutang Usaha
                    [
                        'journal_entry_id' => $journal_entry->id,
                        'account_id' => 4,                              // akun piutang usaha
                        'debit' => 0,
                        'credit' => $payment->total_amount,
                    ],
                ];
                break;
            default: ;
        }
        $journal_entry->journal_entry_details()->createMany($details);

        // post journal to GL
        $journal_entry->postJournalEntrytoGeneralLedger();

        $journal_entry->generateNumber();
        $journal_entry->save();
    }
}
