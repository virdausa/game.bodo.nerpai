<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store\StoreProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StoreProductController extends Controller
{
    public function index()
    {
        $store_id = Session::get('company_store_id');
        $store_products = StoreProduct::with('store', 'product')->where('store_id', $store_id)->get();

        return view('store_products.index', compact('store_products'));
    }

    public function create()
    {
        $products = Product::all()->where('status', 'Active');
        return view('store_products.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'store_price' => 'nullable|decimal:20,2',
            'status' => 'required|enum:active,inactive',
            'notes' => 'nullable|text'
        ]);

        $store_id = Session::get('company_store_id');
        $store_product = StoreProduct::create(array_merge($validated, ['store_id' => $store_id]));

        return redirect()->route('store_products.index')->with('success', "Store Product {$store_product->store->name} created successfully.");
    }

    public function show(string $id)
    {
        $store_product = StoreProduct::with('product')->findOrFail($id);
        return view('store_products.show', compact('store_product'));
    }

    public function edit(string $id)
    {
        $store_product = StoreProduct::with('product')->findOrFail($id);
        return view('store_products.edit', compact('store_product'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'store_price' => 'nullable|decimal:20,2',
            'status' => 'required|enum:active,inactive',
            'notes' => 'nullable|text'
        ]);

        $store_product = StoreProduct::with('store')->findOrFail($id);
        $store_product->update($validated);

        return redirect()->route('store_products.index')->with('success', "Store Product {$store_product->store->name} updated successfully.");
    }

    public function destroy(string $id)
    {
        $store_product = StoreProduct::with('store')->findOrFail($id);
        $store_product->delete();
        return redirect()->route('store_products.index')->with('success', "Store Product {$store_product->store->name} deleted successfully.");
    }
}
