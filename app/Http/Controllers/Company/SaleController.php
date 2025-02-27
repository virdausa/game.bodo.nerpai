<?php

namespace App\Http\Controllers\Company;

use App\Models\Company\Sale;
use App\Models\SalesProduct;
use App\Models\Product;
use App\Models\Company\Warehouse;
use App\Models\Company\Courier;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\CustomerComplaint;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class SaleController extends Controller
{
	public function index(Request $request)
	{
		$sales = Sale::with(['warehouse', 'customer'])->orderBy('sale_date', 'desc')->get();
		return view('sales.index', compact('sales'));
	}


	public function create()
	{
		$warehouses = Warehouse::all();
		$products = Product::all();
		$couriers = Courier::all(); // Fetch couriers
		$customers = Customer::all();
		return view('sales.create', compact('warehouses', 'products', 'couriers', 'customers'));
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'customer_id' => 'required|integer',
			'sale_date' => 'required|date',
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
			'sale_date' => $validated['sale_date'],
			'customer_id' => $validated['customer_id'],
			'warehouse_id' => $validated['warehouse_id'],
			'employee_id' => $employee->id,
			'total_amount' => collect($validated['products'])->sum(function ($product) {
				return $product['quantity'] * $product['price'];
			}),
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
		return view('sales.show', compact('sale'));
	}


	public function edit($id)
	{
		$sale = Sale::with('products')->findOrFail($id);
		$customers = Customer::all();
		$warehouses = Warehouse::all();
		$products = Product::all();
		$couriers = Courier::all(); // Fetch couriers

		return view('sales.edit', compact('sale', 'warehouses', 'products', 'couriers', 'customers'));
	}


	public function update(Request $request, $id)
	{
		$validated = $request->validate([
			'sale_date' => 'required|date',
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
		$sale->update([
			'customer_id' => $validated['customer_id'],
			'so_number' => $sale->generateSoNumber(),
			'sale_date' => $validated['sale_date'],
			'warehouse_id' => $validated['warehouse_id'],
			'courier_id' => $validated['courier_id'] ?? NULL, // Update courier in the same call
			'shipping_fee_discount' => $validated['shipping_fee_discount'] ?? 0,
			'estimated_shipping_fee' => $validated['estimated_shipping_fee'],
			'employee_id' => $employee->id,
		]);
		

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

	public function updateStatus(Sale $sale, $status)
	{
		// Set status to "Unpaid" and request outbound
		if ($sale->status == 'Planned' && $status == 'Unpaid') {
			$sale->status = $status;
			$sale->save();

			// Create an inbound request when moving to "In Transit"
			$requestedQuantities = [];
			foreach ($sale->products as $product) {
				$requestedQuantities[$product->id] = $product->pivot->quantity;
			}

			OutboundRequest::create([
				'sales_order_id' => $sale->id,
				'warehouse_id' => $sale->warehouse_id,
				'requested_quantities' => $requestedQuantities,
				'received_quantities' => [],
				'status' => 'Requested',
				'notes' => 'Outbound request created upon status change to Unpaid',
			]);
		}

		// Set status to "Pending Shipment" after confirming payment
		if ($status == 'Pending Shipment') {
			// Check if the sale is in the correct status
			if ($sale->status !== 'Unpaid') {
				return redirect()->back()->with('error', 'This sale cannot be marked as paid.');
			}

			// Update the related Outbound Request status
			$outboundRequest = OutboundRequest::where('sales_order_id', $sale->id)
				->where('status', 'Pending Confirmation')
				->latest()->first();
			if ($outboundRequest) {
				$outboundRequest->status = 'Packing & Shipping';
				$outboundRequest->save();
			}

			$sale->update(['status' => $status]);
			return redirect()->route('sales.show', $sale->id)
				->with('success', 'Sale marked as paid and is now pending shipment.');
		}

		$sale->update(['status' => $status]);

		return redirect()->route('sales.edit', $sale->id)
			->with('success', 'Status updated successfully!');
	}
}
