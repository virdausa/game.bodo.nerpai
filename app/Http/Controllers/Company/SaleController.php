<?php

namespace App\Http\Controllers\Company;

use App\Models\Company\Sale;
use App\Models\SalesProduct;
use App\Models\Company\Product;
use App\Models\Company\Warehouse;
use App\Models\Company\Courier;
use App\Models\Company\Customer;
use App\Models\Employee;
use App\Models\CustomerComplaint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Company\SaleInvoice;
use App\Models\Company\Outbound;
use App\Models\Company\OutboundProduct;

use App\Models\Space\Company;

class SaleController extends Controller
{
	public function index(Request $request)
	{
		$sales = Sale::with(['warehouse', 'consignee'])->orderBy('updated_at', 'desc')->get();
		return view('company.sales.index', compact('sales'));
	}


	public function create()
	{
		$warehouses = Warehouse::all();
		$products = Product::all();
		$couriers = Courier::all(); // Fetch couriers
		$customers = Customer::all();
		$companies = Company::all();

		return view('company.sales.create', compact('warehouses', 'products', 'couriers', 'customers', 'companies'));
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'customer_id' => 'required|exists:customers,id',
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
			'consignee_type' => 'CUST',
			'consignee_id' => $validated['customer_id'],
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
		$sale = Sale::with(['products', 'invoices', 'courier', 'outbounds'])->findOrFail($id);

		return view('company.sales.show', compact('sale'));
	}


	public function edit($id)
	{
		$sale = Sale::with('products')->findOrFail($id);
		$customers = Customer::all();
		$warehouses = Warehouse::all();
		$products = Product::all();
		$couriers = Courier::all(); // Fetch couriers
		$companies = Company::all();

		return view('company.sales.edit', compact('sale', 'warehouses', 'products', 'couriers', 'customers', 'companies'));
	}


	public function update(Request $request, $id)
	{
		$validated = $request->validate([
			'date' => 'required|date',
			'customer_id' => 'required|exists:customers,id',
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

		$sale->consignee_type = 'CUST';
		$sale->consignee_id = $validated['customer_id'];

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

		return redirect()->route('sales.show', $sale->id)->with('success', "Sale {$sale->so_number} updated successfully.");
	}


	public function destroy($id)
	{
		$sale = Sale::findOrFail($id);
		$sale->delete();

		return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
	}


	public function handleAction(Request $request, $sales, $action){
		$sale = Sale::findOrFail($sales);

		switch($action){
			case 'SO_REQUEST':
				$this->sendSORequestToCustomer($sale);
				break;
			case 'SO_CONFIRMED':
				return $this->inputInvoiceForCustomer($sale);
				break;
			case 'SO_DP_CONFIRMED':
				$this->confirmPaymenttoCustomer($sale);
				break;
			case 'SO_OUTBOUND_REQUEST':
				return $this->requestOutboundforSale($request, $sale);
				break;
			case 'SO_PAYMENT_COMPLETION':
				$this->paymentCompletion($sale);
				break;
			case 'SO_COMPLETED':
				$this->completeSale($sale);
				break;
			default:
				abort(404);
		}

		return redirect()->route('sales.show', $sale->id)->with('success', "Sale {$sale->number} updated successfully.");
	}

	public function sendSORequestToCustomer($sale){
		$sale->status = 'SO_REQUEST';

		// Create Request to Customer if connected

		$sale->save();
	}

	public function inputInvoiceForCustomer($sale){
		$sale_invoice = SaleInvoice::create([
			'sale_id' => $sale->id,
			'date' => date('Y-m-d'),
			'cost_products' => $sale->total_amount,
			'cost_packing' => 0,
			'cost_freight' => $sale->estimated_shipping_fee,
			'total_amount' => $sale->total_amount,
			'status' => 'unconfirmed',
		]);
		$sale_invoice->generateNumber();
		$sale_invoice->save();
		
		$sale->status = 'SO_CONFIRMED';
		$sale->save();

		return redirect()->route('sale_invoices.edit', $sale_invoice->id)->with('success', "Invoice {$sale_invoice->number} created successfully");
	}

	public function confirmPaymenttoCustomer($sale){
		$sale->status = 'SO_DP_CONFIRMED';

		// notify supplier

		$sale->save();
	}
	
	public function requestOutboundForSale(Request $request, $sale) {
        // check shipper
        if($sale->warehouse_id) {
            $this->requestOutboundWarehouse($request, $sale);
        // } else if($sale->shipper_type == 'ST') {
        //     $this->requestOutboundStore($request, $sale);
        } else {
            abort(404);
        }

        $sale->status = 'SO_OUTBOUND_REQUEST';
        $sale->save();
        
        return redirect()->route('sales.show', $sale->id)->with('success', "Outbound {$sale->number} request successfully.");
    }

    public function requestOutboundWarehouse(Request $request, $sale) {
        $employee = session('employee');
        
        $outbound = Outbound::create([
            'warehouse_id' => $sale->warehouse_id,
            'source_type' => 'SO',
            'source_id' => $sale->id,
            'employee_id' => $employee->id,
            'date' => now()->format('Y-m-d'),
            'status' => 'OUTB_REQUEST',
        ]);
        $outbound->generateNumber();
        $outbound->save();
        
        if(isset($sale->products)) {
            foreach ($sale->products as $product) {
                OutboundProduct::create([
                    'outbound_id' => $outbound->id,
                    'product_id' => $product->pivot->product_id,
                    'quantity' => $product->pivot->quantity,
                    'cost_per_unit' => $product->pivot->price,
                    'warehouse_location_id' => '1',
                ]);
            }
        }
    }

    public function requestOutboundStore(Request $request, $sale) {
    
    }

	public function paymentCompletion($sale){
		$sale->status = 'SO_PAYMENT_COMPLETION';

		$sale->save();
	}

	public function completeSale($sale)
	{	
		$sale->status = 'SO_COMPLETED';

		$sale->save();
	}
}
