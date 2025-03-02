<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Company\Store;
use App\Models\Store\StoreRestock;
use App\Models\Company\Warehouse;
use App\Models\Product;

class RestockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $restocks = StoreRestock::with('store_employee')->get();
        $stores = Store::all();

        return view('company.restocks.index', compact('restocks', 'stores'));
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
        $store_restock = StoreRestock::with('products')->findOrFail($id);

        return view('company.restocks.show', compact('store_restock'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $store_restock = StoreRestock::with('products')->findOrFail($id);
        $warehouses = Warehouse::all();
        $products = Product::all();

        return view('company.restocks.edit', compact('store_restock', 'warehouses', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'restock_date' => 'required|date',
            'warehouse_id' => 'required|exists:warehouses,id',
            'admin_notes' => 'nullable',
            'team_notes' => 'nullable',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric',
            'products.*.notes' => 'nullable',
        ]);

        $store_restock = StoreRestock::findOrFail($id);
        $store_restock->update($validated);
        $store_restock->generateNumber();
        
        // products
        $this->SyncRestockProducts($request, $store_restock);
        
        $store_restock->save();

        return redirect()->route('restocks.index')->with('success', "Restock {$store_restock->number} updated successfully.");
    }


    public function SyncRestockProducts($request, $store_restock){
        $restock_products = [];

        if(isset($request->products)){
            foreach ($request->products as $product) {
                $restock_products[$product['product_id']] = [
                    'quantity' => $product['quantity'],
                    'notes' => $product['notes'],
                ];
            }
        }

        $store_restock->products()->sync($restock_products);

        $store_restock->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
