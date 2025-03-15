<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Company\Store;
use App\Models\Company\Warehouse;
use App\Models\Company\Product;
use App\Models\Company\Outbound;
use App\Models\Company\OutboundProduct;

use App\Models\Company\Inventory;
use App\Models\Company\InventoryTransfer;
use App\Models\Company\Courier;

class InventoryTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = session('layout');

        $inventory_transfers = InventoryTransfer::orderBy('created_at', 'desc')->get();
        $stores = Store::all();
        $warehouses = Warehouse::all();

        $view_create = 'company.inventory_transfers.create';
        if($layout == 'warehouse' || $layout == 'store') {
            $consignee_type = $layout == 'store' ? 'ST' : 'WH';
            $consignee_id = session('company_' . $layout . '_id');

            $inventory_transfers = InventoryTransfer::where('consignee_type', $consignee_type)
                                                    ->where('consignee_id', $consignee_id)
                                                    ->orderBy('created_at', 'desc')->get();
            $view_create = 'company.inventory_transfers.create-request';
        }

        return view('company.inventory_transfers.index', compact('inventory_transfers', 'stores', 'warehouses', 'view_create'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'consignee_type' => 'required|string|max:255',
            'consignee_id' => 'required|numeric',
            'admin_notes' => 'nullable|string|max:255',
        ]);

        $employee = session('employee');

        $validated['admin_id'] = $employee->id; 

        return $this->createRequest($validated);
    }

    public function storeRequest(Request $request) {
        $validated = $request->validate([
            'team_notes' => 'nullable|string|max:255',
        ]);

        $layout = session('layout');
        $employee = session('employee');
        $consignee_type = $layout == 'store' ? 'ST' : 'WH';
        $consignee_id = $layout == 'store' ? session('company_store_id') : session('company_warehouse_id');

        $validated['consignee_type'] = $consignee_type;
        $validated['consignee_id'] = $consignee_id;
        $validated['team_id'] = $employee->id;

        return $this->createRequest($validated);
    }

    public function createRequest($validated) {
        $inventory_transfer = InventoryTransfer::create($validated);
        
        $inventory_transfer->date = now()->format('Y-m-d');
        $inventory_transfer->generateNumber();
        $inventory_transfer->status = 'ITF_REQUEST';

        $inventory_transfer->save();

        return redirect()->route('inventory_transfers.index')->with('success', "Inventory transfer request: {$inventory_transfer->number} created successfully. :)");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $inventory_transfers = InventoryTransfer::with('products', 'outbounds')->findOrFail($id);


        // validasi ready to outbound
        $itf_ready_to_outbound = false;
        if($inventory_transfers->status == 'ITF_PROCESS') {
            $itf_ready_to_outbound = !is_null($inventory_transfers->shipper_type) &&
                                    !is_null($inventory_transfers->shipper_id) &&
                                    !is_null($inventory_transfers->courier_id);
            $itf_ready_to_outbound = $inventory_transfers->products->count() > 0;
        }

        return view('company.inventory_transfers.show', compact('inventory_transfers', 'itf_ready_to_outbound'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $inventory_transfer = InventoryTransfer::with('products')->findOrFail($id);
        $products = Product::all();
        $couriers = Courier::all();
        $inventories = [];

        // pick inventories from shipper 
        $shipper_type = $inventory_transfer->shipper_type;
        $shipper_id = $inventory_transfer->shipper_id;
        if($shipper_type == 'WH') {
            $inventories = Inventory::with(['product', 'warehouse'])
                        ->where('warehouse_id', $shipper_id)
                        ->get();
        }
         
        // cant sent to itself        
        $consignee_type = $inventory_transfer->consignee_type;
        $consignee_id = $inventory_transfer->consignee_id;

        if($consignee_type == 'ST') {
            $stores = Store::where('id', '!=', $consignee_id)->get();
        } else {
            $stores = Store::where();
        }
        
        if($consignee_type == 'WH') {
            $warehouses = Warehouse::where('id', '!=', $consignee_id)->get();
        } else {
            $warehouses = Warehouse::all();
        }

        return view('company.inventory_transfers.edit', compact('inventory_transfer', 'warehouses', 'products', 'stores', 'couriers', 'inventories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'shipper_type' => 'required|string|max:255',
            'shipper_id' => 'required|numeric',
            'courier_id' => 'required|exists:couriers,id',
            'admin_notes' => 'nullable',
            'team_notes' => 'nullable',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric',
            'products.*.notes' => 'nullable',
        ]);

        $inventory_transfer = InventoryTransfer::findOrFail($id);
        $inventory_transfer->update($validated);

        $inventory_transfer->origin_address = $inventory_transfer->shipper?->address;
        $inventory_transfer->destination_address = $inventory_transfer->consignee?->address;

        $inventory_transfer->generateNumber();
        
        $itf_before = $inventory_transfer;

        // products
        $this->SyncInventoryTransferProducts($request, $inventory_transfer);
        
        //dd($inventory_transfer, $itf_before);

        $inventory_transfer->save();

        return redirect()->route('inventory_transfers.show', $inventory_transfer->id)->with('success', "Inventory transfer request: {$inventory_transfer->number} updated successfully. :)");
    }


    public function SyncInventoryTransferProducts($request, $inventory_transfer){
        $transfer_products = [];

        if(isset($request->products)){
            foreach ($request->products as $product) {
                $transfer_products[] = [
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'notes' => $product['notes'],
                ];
            }
        }

        $inventory_transfer->products()->sync($transfer_products);

        $inventory_transfer->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function handleAction(Request $request, $inventory_transfer_id, $action) {
		$inventory_transfer = InventoryTransfer::findOrFail($inventory_transfer_id);

		switch ($action) {
			case 'ITF_PROCESS':
				// get shipment confirmation
				return $this->processTransfer($inventory_transfer);
                break;
            case 'ITF_OUTBOUND':
                return $this->requestOutboundForInventoryTransfer($request, $inventory_transfer);
                break;
			default:
				abort(404);
		}

        return redirect()->route('inventory_transfers.index')->with('success', "Transfer request {$inventory_transfer->number} updated successfully.");
	}


    public function processTransfer($inventory_transfer) {
        $inventory_transfer->status = 'ITF_PROCESS';
        $inventory_transfer->save();

        return redirect()->route('inventory_transfers.show', $inventory_transfer->id)->with('success', "Transfer request {$inventory_transfer->number} updated successfully.");
    }


    public function requestOutboundForInventoryTransfer(Request $request, $inventory_transfer) {
        // check shipper
        if($inventory_transfer->shipper_type == 'WH') {
            $this->requestOutboundWarehouse($request, $inventory_transfer);
        } else if($inventory_transfer->shipper_type == 'ST') {
            $this->requestOutboundStore($request, $inventory_transfer);
        } else {
            abort(404);
        }

        // $inventory_transfer->status = 'ITF_OUTBOUND';
        // $inventory_transfer->save();
        
        return redirect()->route('inventory_transfers.show', $inventory_transfer->id)->with('success', "Outbound {$inventory_transfer->number} request successfully.");
    }

    public function requestOutboundWarehouse(Request $request, $inventory_transfer) {
        $employee = session('employee');
        
        $outbound = Outbound::create([
            'warehouse_id' => $inventory_transfer->shipper_id,
            'source_type' => 'ITF',
            'source_id' => $inventory_transfer->id,
            'employee_id' => $employee->id,
            'date' => now()->format('Y-m-d'),
            'status' => 'OUTB_REQUEST',
        ]);
        $outbound->generateNumber();
        $outbound->save();
        
        if(isset($inventory_transfer->products)) {
            foreach ($inventory_transfer->products as $product) {
                OutboundProduct::create([
                    'outbound_id' => $outbound->id,
                    'product_id' => $product->pivot->product_id,
                    'quantity' => $product->pivot->quantity,
                    'cost_per_unit' => $product->pivot->cost_per_unit,
                    'warehouse_location_id' => '1',
                ]);
            }
        }
    }

    public function requestOutboundStore(Request $request, $inventory_transfer) {
    
    }
}
