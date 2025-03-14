<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Company\Finance\Account;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('company.reports.index');
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
        switch($id){
            case 'profit-and-loss':
                $accounts = Account::with('account_type', 'parent', 'children')->get();
                return view('company.reports.finance.profit-and-loss', compact('id', 'accounts'));
            case 'cashflow':
                $accounts = Account::with('account_type', 'parent', 'children')->get();
                return view('company.reports.finance.cashflow', compact('id', 'accounts'));
            case 'balance-sheet':
                $accounts = Account::with('account_type', 'parent', 'children')->get();
                return view('company.reports.finance.balance-sheet', compact('id', 'accounts'));
            default:
                ;
        }

        return view('company.reports.index');
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
}
