<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Company\Warehouse;
use App\Models\Store\StoreWarehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StoreWarehouseController extends Controller
{
    public function index()
    {
        $store_id = Session::get('company_store_id');
        $store_warehouse = StoreWarehouse::with('warehouse')->where('store_id', $store_id)->get();

        return view('store.warehouse.index', compact('store_warehouse'));
    }

    public function show(string $id)
    {
        $store_warehouse = StoreWarehouse::with('warehouse')->findOrFail($id);

        return view('store.warehouse.show', compact('store_warehouse'));
    }

    public function create()
    {
        $warehouses = Warehouse::all()->where('status', 'active');

        return view('store.warehouse.create', compact('warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => 'nullable|integer',
            'status' => 'required|enum:active,inactive',
            'notes' => 'nullable|text'
        ]);

        $store_id = Session::get('company_store_id');
        $store_warehouse = StoreWarehouse::create(array_merge($validated, ['store_id' => $store_id]));

        return redirect()->route('store_warehouse.index')->with('success', "Store Warehouse {$store_warehouse->warehouse->name} created successfully.");
    }

    public function edit(string $id)
    {
        $store_warehouse = StoreWarehouse::all()->findOrFail($id);

        return view('store.warehouse.edit', compact('store_warehouse'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'warehouse_id' => 'nullable|integer',
            'status' => 'required|enum:active,inactive',
            'notes' => 'nullable|text'
        ]);

        $store_warehouse = StoreWarehouse::all()->findOrFail($id);
        $store_warehouse->update($validated);

        return redirect()->route('store_warehouse.index')->with('success', "Store Warehouse {$store_warehouse->warehouse->name} updated successfully.");
    }

    public function destroy(string $id)
    {
        $store_warehouse = StoreWarehouse::all()->findOrFail($id);
        $store_warehouse->delete();

        return redirect()->route('store_warehouse.index')->with('success', "Store Warehouse {$store_warehouse->warehouse->name} deleted successfully.");
    }
}
