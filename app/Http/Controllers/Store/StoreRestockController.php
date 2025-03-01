<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Company\Store;
use App\Models\Store\StoreRestock;

class StoreRestockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $store_id = session('company_store_id');
        $store_restocks = StoreRestock::with('store_employee')
                                        ->where('store_id', $store_id)
                                        ->get();

        return view('store.store_restocks.index', compact('store_restocks'));
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
        $validated = $request->validate([
            'team_notes' => 'nullable|string|max:255',
        ]);

        $store = Store::findOrFail(session('company_store_id'));
        $store_employee_id = session('company_store_employee_id');

        $store_restock = StoreRestock::create([
            'store_id' => $store->id,
            'restock_date' => now()->format('Y-m-d'),
            'store_employee_id' => $store_employee_id,
            'status' => 'STR_REQUEST',
            'total_amount' => 0,
            'team_notes' => $validated['team_notes'],
        ]);
        $store_restock->generateNumber();
        $store_restock->save();

        // notify admin

        return redirect()->route('store_restocks.index')->with('success', 'Restock request created successfully. :)');
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


    public function cancelRequest(Request $request, $id)
    {
        $store_restock = StoreRestock::findOrFail($id);
        $store_restock->update([
            'status' => 'STR_CANCELLED',
        ]);

        return redirect()->route('store_restocks.index')->with('success', "Restock request {$store_restock->number} cancelled successfully. :)");
    }
}
