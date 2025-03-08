<?php

namespace App\Http\Controllers\Company;

use App\Models\Company\Sale;
use App\Models\SalesProduct;
use App\Models\Product;
use App\Models\Company\Warehouse;
use App\Models\Company\Courier;
use App\Models\Company\Customer;
use App\Models\Employee;
use App\Models\CustomerComplaint;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class SaleController extends Controller
{
	public function index(Request $request)
	{
		$sales = Sale::with(['warehouse', 'customer'])->orderBy('date', 'desc')->get();
		return view('company.sales.index', compact('sales'));
	}


	public function create()
	{
		$warehouses = Warehouse::all();
		$products = Product::all();
		$couriers = Courier::all(); // Fetch couriers
		$customers = Customer::all();

		return view('company.sales.create', compact('warehouses', 'products', 'couriers', 'customers'));
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'customer_id' => 'required|integer',
			'date' => 'required|date',
			'warehouse_id' => 'required|exists:warehouses,id',
			'products' => 'required|array', // Expecting products as an array
			'products.*.product_id' => 'required|exists:products,id',
			'products.*.quantity' => 'required|integer|min:1',
			'products.*.price' => 'required|numeric|min:0',
			'products.*.note' => 'nullable|string', // Note for each product
		]);
		
		$employee = session('employee');

		// Create sale
		$sale = Sale::create([
			'date' => $validated['date'],
			'customer_id' => $validated['customer_id'],
			'warehouse_id' => $validated['warehouse_id'],
			'employee_id' => $employee->id,
			'total_amount' => collect($validated['products'])->sum(function ($product) {
				return $product['quantity'] * $product['price'];
			}),
			'status' => 'SO_OFFER',
		]);
		$sale->generateSoNumber();
		$sale->save();

		// Create Sales Products
		foreach ($validated['products'] as $product) {
			$sale->products()->attach($product['product_id'], [
				'quantity' => $product['quantity'],
				'price' => $product['price'],
				'total_cost' => $product['quantity'] * $product['price'],
				'notes' => $product['note'] ?? null,
			]);
		}

		return redirect()->route('sales.index')->with('success', "Sale {$sale->so_number} created successfully.");
	}


	public function show($id)
	{
		$sale = Sale::with(['products'])->findOrFail($id);
		return view('company.sales.show', compact('sale'));
	}


	public function edit($id)
	{
		$sale = Sale::with('products')->findOrFail($id);
		$customers = Customer::all();
		$warehouses = Warehouse::all();
		$products = Product::all();
		$couriers = Courier::all(); // Fetch couriers

		return view('company.sales.edit', compact('sale', 'warehouses', 'products', 'couriers', 'customers'));
	}


	public function update(Request $request, $id)
	{
		$validated = $request->validate([
			'date' => 'required|date',
			'customer_id' => 'required|integer',
			'warehouse_id' => 'required|exists:warehouses,id',
			'products' => 'required|array', // Validate products as an array
			'products.*.product_id' => 'required|exists:products,id',
			'products.*.quantity' => 'required|integer|min:1',
			'products.*.price' => 'required|numeric|min:0',
			'products.*.notes' => 'nullable|string', // Handle product notes
			'courier_id' => 'nullable|exists:couriers,id', // Validate courier
			'shipping_fee_discount' => 'nullable|numeric|min:0',
			'estimated_shipping_fee' => 'nullable|numeric|min:0',
		]);

		$employee = session('employee');
		
		$sale = Sale::findOrFail($id);
		$sale->update($validated);
		$sale->generateSoNumber();

		// Sync products with updated quantities, prices, and notes
		$totalAmount = 0;
		$productData = [];
		foreach ($validated['products'] as $product) {
			$total_cost = $product['quantity'] * $product['price'];
			$productData[$product['product_id']] = [
				'quantity' => $product['quantity'],
				'price' => $product['price'],
				'total_cost' => $total_cost,
				'notes' => $product['notes'] ?? null, // Handle notes
			];
			$totalAmount += $total_cost;
		}
		$sale->products()->sync($productData);

		// Update total amount
		$sale->update(['total_amount' => $totalAmount]);
		$sale->save();

		return redirect()->route('sales.index')->with('success', "Sale {$sale->so_number} updated successfully.");
	}


	public function destroy($id)
	{
		$sale = Sale::findOrFail($id);
		$sale->delete();

		return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
	}
}
