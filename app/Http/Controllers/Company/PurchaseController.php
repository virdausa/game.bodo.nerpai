<?php

namespace App\Http\Controllers\Company;

use App\Models\Company\Purchase; // Ensure you import the Purchase model
use App\Models\Company\PurchaseInvoice;
use App\Models\Company\Product;
use App\Models\Company\Warehouse;
use App\Models\InventoryMovement;
use App\Models\InboundRequest;
use App\Models\Company\Supplier;
use App\Models\Company\Shipment;
use Illuminate\Http\Request;

use App\Services\Company\Finance\JournalEntryService;

use App\Http\Controllers\Controller;

class PurchaseController extends Controller
{
	// Method to show all purchases
	public function index()
	{
		//$purchases = Purchase::all(); // Retrieve all purchase records
		$purchases = Purchase::with('shipments', 'employee')->orderBy('id', 'desc')->get();
		return view('company.purchases.index', compact('purchases')); // Pass data to the view
	}

	// You can add other methods here for creating, updating, deleting purchases as needed
	// Show form to create a new purchase
	public function create()
	{
		$suppliers = Supplier::all();
		$warehouses = Warehouse::all();
		$products = Product::all();
		return view('company.purchases.create', compact('products', 'warehouses', 'suppliers'));
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


	public function duplicate($id) 
	{
		$purchase = Purchase::findOrFail($id);

		$newPurchase = $purchase->replicate()->fill([
			'status' => 'PO_PLANNED',
		]);
		$newPurchase->po_number = null;
		$newPurchase->save();

		// replicate products
		$total_amount = 0;
		foreach ($purchase->products as $product) {
			$total_cost = $product->pivot->quantity * $product->pivot->buying_price;
			$newPurchase->products()->attach($product->pivot->product_id, [
				'quantity' => $product->pivot->quantity,
				'buying_price' => $product->pivot->buying_price,
				'total_cost' => $total_cost
			]);
			$total_amount += $total_cost;
		}
		$newPurchase->total_amount = $total_amount;

		dd($newPurchase->products);

		$newPurchase->generatePoNumber();
		$newPurchase->save();

		return redirect()->route('purchases.edit', $newPurchase->id)->with('success', "Purchase {$newPurchase->po_number} created successfully.");
	}


	public function show($id)
	{
		$purchase = Purchase::with(['products', 'warehouse', 'shipments'])->findOrFail($id);
		return view('company.purchases.show', compact('purchase'));
	}


	// Show form to edit a specific purchase
	public function edit($id)
	{
		$purchase = Purchase::with('products')->findOrFail($id);
		$suppliers = Supplier::all();
		$warehouses = Warehouse::all();
		$products = Product::all(); // Fetch all available products

		return view('company.purchases.edit', compact('purchase', 'warehouses', 'products', 'suppliers'));
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
		if(isset($request->products)) {
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

	

	public function handleAction(Request $request, $purchases, $action){
		$purchase = Purchase::findOrFail($purchases);

		switch($action){
			case 'PO_REQUEST_TO_SUPPLIER':
				$this->sendPORequestToSupplier($purchase);
				break;
			case 'PO_CONFIRMED':
				return $this->inputInvoiceFromSupplier($purchase);
				break;
			case 'PO_DP_CONFIRMED':
				$this->confirmPaymenttoSupplier($purchase);
				break;
			case 'PO_SHIPMENT_CONFIRMED':
				return $this->inputShipmentFromSupplier($purchase);
				break;
			case 'PO_COMPLETE_PAYMENT':
				$this->completePaymenttoSupplier($purchase);
				break;
			case 'PO_COMPLETED':
				$this->completePurchase($purchase);
				break;
			default:
				abort(404);
		}

		return redirect()->route('purchases.show', $purchase->id)->with('success', "Purchase {$purchase->po_number} updated successfully.");
	}

	public function sendPORequestToSupplier($purchase){
		$purchase->status = 'PO_REQUEST_TO_SUPPLIER';

		// Create Request to Supplier if connected

		$purchase->save();
	}

	public function inputInvoiceFromSupplier($purchase){
		$purchase->status = 'PO_CONFIRMED';
		$purchase->save();

		$purchase_invoice = PurchaseInvoice::create([
			'purchase_id' => $purchase->id,
			'date' => date('Y-m-d'),
			'cost_products' => $purchase->total_amount,
			'total_amount' => $purchase->total_amount,
			'status' => 'unconfirmed',
		]);
		$purchase_invoice->generateInvoiceNumber();
		$purchase_invoice->save();

		return redirect()->route('purchase_invoices.edit', $purchase_invoice->id)->with('success', "Invoice {$purchase_invoice->number} created successfully");
	}

	public function confirmPaymenttoSupplier($purchase){
		$purchase->status = 'PO_DP_CONFIRMED';

		// notify supplier

		$purchase->save();
	}
	
	public function inputShipmentFromSupplier($purchase){
		$purchase->status = 'PO_SHIPMENT_CONFIRMED';
		$purchase->save();
		
		$shipment = Shipment::create([
			'shipper_type' => 'SUP',
			'shipper_id' => $purchase->supplier_id,
			'origin_address' => $purchase->supplier->address,
			'consignee_type' => 'WH',
			'consignee_id' => $purchase->warehouse_id,
			'destination_address' => $purchase->warehouse->address,

			'transaction_type' => 'PO',
			'transaction_id' => $purchase->id,

			'ship_date' => date('Y-m-d'),
			'status' => 'SHP_IN_TRANSIT',
		]);
		$shipment->generateShipmentNumber();
		$shipment->save();

		return redirect()->route('shipments.edit', $shipment->id)->with('success', "Shipment {$shipment->shipment_number} created successfully");
	}

	public function completePaymenttoSupplier($purchase){
		$purchase->status = 'PO_COMPLETE_PAYMENT';
	
		$purchase->save();
	}

	public function completePurchase($purchase){
		// notify supplier

		// finish journal
		$this->completePurchaseJournal($purchase);

		$purchase->status = 'PO_COMPLETED';
	
		$purchase->save();
	}

	public function completePurchaseJournal($purchase){
		// $invoices = $purchase->purchase_invoices;

		// $details = [];
		// foreach($invoices as $invoice){
		// 	$details['cost_products'] += $invoice->cost_products;
		// 	$details['vat_input'] += $invoice->vat_input;
		// 	$details['cost_packing'] += $invoice->cost_packing;
		// 	$details['cost_insurance'] += $invoice->cost_insurance;
		// 	$details['cost_freight'] += $invoice->cost_freight;
		// 	$details['total_amount'] += $invoice->total_amount;
		// }

		// $journalService = app(JournalEntryService::class);
		// $journalService->addJournalEntry([
		// 	'source_type' => 'PO',
		// 	'source_id' => $purchase->id,
		// 	'date' => date('Y-m-d'),
		// 	'type' => 'PO',
		// 	'description' => 'Pembelian Barang',
		// 	'total' => $details['total_amount'],
		// ], [
		// 	[
		// 		'account_id' => 5,                  // inventory
		// 		'credit' => $details['cost_products'],
		// 	],
		// ]);
	}
}
