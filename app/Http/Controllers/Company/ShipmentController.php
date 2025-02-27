<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company\Shipment;
use App\Models\Company\Courier;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shipments = Shipment::all();
        return view('company.shipments.index', compact('shipments'));
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
        $shipment = Shipment::findOrFail($id);
        $couriers = Courier::all();

        return view('company.shipments.edit', compact('shipment', 'couriers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'courier_id' => 'required|exists:couriers,id',
            'ship_date' => 'required|date',
            'tracking_number' => 'required|string|max:255',
            'shipping_fee' => 'required|numeric',
            'payment_rules' => 'nullable|string|max:255',
            'packing_quantity' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        $shipment = Shipment::findOrFail($id);
        $shipment->update($validated);
        $shipment->generateShipmentNumber();
        $shipment->save();

        $referrer = $request->referrer;
        return redirect()->to($referrer ?? route('shipments.index'))->with('success', "Shipment {$shipment->shipment_number} updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
