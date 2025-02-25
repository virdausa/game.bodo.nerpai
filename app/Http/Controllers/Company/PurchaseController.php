<?php

namespace App\Http\Controllers\Company;

use App\Models\Company\Purchase; // Ensure you import the Purchase model
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\InventoryHistory;
use App\Models\InboundRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class PurchaseController extends Controller
{
	// Method to show all purchases
	public function index()
	{
		//$purchases = Purchase::all(); // Retrieve all purchase records
		$purchases = Purchase::with('shipments', 'employee')->orderBy('id', 'desc')->get();
		return view('purchases.index', compact('purchases')); // Pass data to the view
	}

	// You can add other methods here for creating, updating, deleting purchases as needed
	// Show form to create a new purchase
	public function create()
	{
		$suppliers = Supplier::all();
		$warehouses = Warehouse::all();
		$products = Product::all();
		return view('purchases.create', compact('products', 'warehouses', 'suppliers'));
	}


	// Store the new purchase
	public function store(Request $request)
	{
		// dd($request->all());
		$request->validate([
			'supplier_id' => 'required|exists:suppliers,id',
			'po_date' => 'required|date',
			'warehouse_id' => 'required|exists:warehouses,id',
			'products.*.product_id' => 'required|exists:products,id',
			'products.*.quantity' => 'required|numeric|min:1',
			'products.*.buying_price' => 'required|numeric|min:0',
		]);

		$totalAmount = 0;
		foreach ($request->products as $productData) {
			$totalAmount += $productData['quantity'] * $productData['buying_price'];
		}

		$purchase = Purchase::create([
			'po_date' => $request->po_date,
			'supplier_id' => $request->supplier_id,
			'warehouse_id' => $request->warehouse_id,
			'total_amount' => $totalAmount,
			'status' => 'Planned',
			'employee_id' => session('employee')->id,
		]);
		$purchase->generatePoNumber();
		$purchase->save();

		foreach ($request->products as $productData) {
			$purchase->products()->attach($productData['product_id'], [
				'quantity' => $productData['quantity'],
				'buying_price' => $productData['buying_price'],
				'total_cost' => $productData['quantity'] * $productData['buying_price']
			]);
		}

		return redirect()->route('purchases.index')->with('success', "Purchase {$purchase->po_number} created successfully.");
	}


	public function show($id)
	{
		$purchase = Purchase::with(['products', 'warehouse', 'shipments'])->findOrFail($id);
		return view('purchases.show', compact('purchase'));
	}


	// Show form to edit a specific purchase
	public function edit($id)
	{
		$suppliers = Supplier::all();
		$purchase = Purchase::with('products')->findOrFail($id);
		$warehouses = Warehouse::all();
		$products = Product::all(); // Fetch all available products

		return view('purchases.edit', compact('purchase', 'warehouses', 'products', 'suppliers'));
	}


	public function update(Request $request, $id)
	{
		$request->validate([
			'supplier_id' => 'required',
			'po_date' => 'required|date',
			'warehouse_id' => 'required|exists:warehouses,id',
			'products.*.product_id' => 'required|exists:products,id',
			'products.*.quantity' => 'required|numeric|min:1',
			'products.*.buying_price' => 'required|numeric|min:0',
		]);

		$purchase = Purchase::findOrFail($id);
		$purchase->update([
			'supplier_id' => $request->supplier_id,
			'po_number' => $purchase->generatePoNumber(),
			'po_date' => $request->po_date,
			'warehouse_id' => $request->warehouse_id,
			'supplier_notes' => $request->supplier_notes,
			'admin_notes' => $request->admin_notes,
			'supplier_notes' => $request->supplier_notes,
		]);

		$totalAmount = 0;

		$purchaseProducts = [];
		foreach ($request->products as $product) {
			$quantity = $product['quantity'];
			$buyingPrice = $product['buying_price'];
			$totalCost = $quantity * $buyingPrice;

			$purchaseProducts[$product['product_id']] = [
				'quantity' => $quantity,
				'buying_price' => $buyingPrice,
				'total_cost' => $totalCost,
			];

			$totalAmount += $totalCost;
		}

		// Sync products with updated pivot data
		$purchase->products()->sync($purchaseProducts);

		// Update total amount
		$purchase->total_amount = $totalAmount;
		$purchase->save();

		return redirect()->route('purchases.index')->with('success', "Purchase {$purchase->po_number} updated successfully.");
	}



	// Delete a specific purchase
	public function destroy($id)
	{
		$purchase = Purchase::findOrFail($id);
		$purchase->delete();

		return redirect()->route('purchases.index')->with('success', "Purchase {$purchase->po_number} deleted successfully.");
	}
}
