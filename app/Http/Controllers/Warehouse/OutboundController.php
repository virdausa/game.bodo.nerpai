<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Company\Outbound;
use App\Models\Company\OutboundProduct;
use App\Models\Company\Warehouse;
use App\Models\Company\Shipment;

use App\Models\Company\Product;
use App\Models\Sale;
use App\Models\Company\Inventory;
use App\Models\InventoryMovement;



use Illuminate\Http\Request;

class OutboundController extends Controller
{
    public function index()
    {
		$warehouse = Warehouse::findOrFail(session('company_warehouse_id'));
        $outbounds = Outbound::with('source')
				->where('warehouse_id', $warehouse->id)
				->orderBy('created_at', 'desc')->get();
		$shipments_outgoing = Shipment::where('shipper_type', 'WH')
				->where('shipper_id', $warehouse->id)
				->orderBy('created_at', 'desc')->get();

		return view('warehouse.warehouse_outbounds.index', compact('outbounds', 'shipments_outgoing'));
    }

    public function create()
    {
        // Fetch products and warehouses for the form dropdown
        $products = Product::all();
        $warehouses = Warehouse::all();
        return view('outbound_requests.create', compact('products', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
			'sales_order_id' => 'required|exists:sales,id',
			'warehouse_id' => 'required|exists:warehouses,id',
			'requested_quantities' => 'required|array',
			'requested_quantities.*' => 'required|integer|min:1',
			'notes' => 'nullable|string',
		]);

        Outbound::create([
			'sales_order_id' => $validated['sales_order_id'],
			'warehouse_id' => $validated['warehouse_id'],
			'requested_quantities' => $validated['requested_quantities'],
			'received_quantities' => [],
			'status' => 'Requested',
			'notes' => $validated['notes'] ?? null,
		]);

        return redirect()->route('outbound_requests.index')->with('success', 'Outbound request created.');
    }

	public function show($id)
	{
		$warehouse_id = session('company_warehouse_id');
		$outbound_action_allowed = false;
		
		if($warehouse_id)
		{
			$outbound_action_allowed = true;
		}

		$outbound = Outbound::with([
			'source',
			'shipments',
			'outbound_products.product',
			'outbound_products.warehouse_location',
			'outbound_products' => function ($query) {
				$query->withCount(['inventory as stock' => function ($subQuery) {
					$subQuery->select(DB::raw('SUM(quantity)'))
							->whereNull('deleted_at')
							->whereIn('warehouse_id', [session('company_warehouse_id')]);
				}]);
			}
		])->findOrFail($id);


		return view('warehouse.warehouse_outbounds.show', compact('outbound', 'outbound_action_allowed'));
	}

	public function edit($id)
	{

		$outboundRequest = Outbound::with('sales', 'warehouse')->findOrFail($id);
		$expeditions = Expedition::all(); // Fetch expeditions
		$warehouses = Warehouse::all();
		// dd($outboundRequest);

		$availableLocations = [];
		$outboundRequestLocations = [];
		foreach ($outboundRequest->requested_quantities as $productId => $quantity) {
			$availableLocations[$productId] = Location::join('inventory', 'locations.id', '=', 'inventory.location_id')
				->where('inventory.warehouse_id', $outboundRequest->warehouse_id)
				->where('inventory.product_id', $productId)
				->select('locations.id', 'locations.room', 'locations.rack', 'inventory.quantity')
				->get();
			
			$outboundRequestLocations[$productId] = OutboundRequestLocation::where('outbound_request_id', $id)
				->where('product_id', $productId)
				->get();
		}
		
		//dd($availableLocations);

		return view('outbound_requests.edit', compact('outboundRequest', 'expeditions', 'availableLocations', 'warehouses', 'outboundRequestLocations'));
	}

	public function update(Request $request, $id)
	{
		$outboundRequest = Outbound::findOrFail($id);
		
		//dd($request);
		
		$validated = $request->validate([
			'packing_fee' => 'nullable|numeric|min:0',
			'expedition_id' => 'nullable|exists:expeditions,id',
			'tracking_number' => 'nullable|string',
			'real_volume' => 'nullable|numeric',
			'real_weight' => 'nullable|numeric',
			'real_shipping_fee' => 'nullable|numeric|min:0',
			'notes' => 'nullable|string',
			'locations' => 'array', // Validate locations as an array
			'locations.*.*.location_id' => 'nullable|exists:locations,id',
			'locations.*.*.quantity' => 'nullable|integer|min:1',
			'deleted_locations' => 'nullable|string',
		]);

		$outboundRequest->update($validated);

		if (($request['submit'])) {
			$submit = $request['submit'];
	
			switch ($submit) {
				case 'Verify Stock & Approve':
					return $this->checkStockAvailability($outboundRequest, $validated, $request);
					break;
				case 'Reject Request':
					$this->rejectRequest($outboundRequest);
					break;
				case 'Mark as Shipped':
					// Validate required data for shipping to change status to In Transit
					$this->updateStatus($outboundRequest, 'In Transit');
					break;
				default:
					;
			}
		}
	
		return redirect()->route('outbound_requests.index')
			->with('success', 'Outbound Request updated successfully!');
	}



	public function handleAction(Request $request, $warehouse_outbounds, $action) {
		$outbound = Outbound::findOrFail($warehouse_outbounds);

		switch ($action) {
			case 'OUTB_PROCESS':
				return $this->acceptOutbound($outbound);
			case 'OUTB_IN_TRANSIT':
				return $this->createShipmentFromOutbound($outbound);
				break;
			case 'OUTB_COMPLETED':
				return $this->completeOutbound($outbound);
				break;
			default:
				abort(404);
		}

		return redirect()->route('warehouse_outbounds.show', $outbound->id)->with('success', "Outbound request {$outbound->number} updated successfully.");
	}


	public function acceptOutbound($outbound) 
	{
		$outbound->status = 'OUTB_PROCESS';
		$outbound->save();
	
		return redirect()->route('warehouse_outbounds.show', $outbound->id)->with('success', "Outbound request {$outbound->number} updated successfully.");
	}


	public function createShipmentFromOutbound($outbound) 
	{
		$shipment = Shipment::create([
			'shipper_type' => 'WH',
			'shipper_id' => $outbound->warehouse_id,
			'origin_address' => $outbound->warehouse->address,
			'consignee_type' => $outbound->source->consignee_type,
			'consignee_id' => $outbound->source->consignee_id,
			'destination_address' => $outbound->source->consignee->address,
			'transaction_type' => 'OUTB',
			'transaction_id' => $outbound->id,
			'courier_id' => $outbound->source->courier_id,
			'ship_date' => now()->format('Y-m-d'),
			'status' => 'SHP_IN_TRANSIT',
		]);
		$shipment->generateShipmentNumber();
		$shipment->save();

		$outbound->status = 'OUTB_IN_TRANSIT';
		$outbound->save();
	
		return redirect()->route('shipments.edit', $shipment->id)->with('success', "Shipment {$shipment->shipment_number} created successfully");
	}

	public function completeOutbound($outbound) 
	{
		$outbound->status = 'OUTB_COMPLETED';
		$outbound->save();
	
		return redirect()->route('warehouse_outbounds.show', $outbound->id)->with('success', "Outbound {$outbound->number} updated successfully.");
	}
}
