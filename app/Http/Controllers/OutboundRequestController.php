<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\OutboundRequest;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Inventory;
use App\Models\InventoryHistory;
use App\Models\Expedition;
use App\Models\Location;
use App\Models\OutboundRequestLocation;
use Illuminate\Http\Request;

class OutboundRequestController extends Controller
{
    public function index()
    {
        $outboundRequests = OutboundRequest::with('sales', 'warehouse', 'verifier')
											->orderBy('created_at', 'desc')
											->get();
        return view('outbound_requests.index', compact('outboundRequests'));
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

        OutboundRequest::create([
			'sales_order_id' => $validated['sales_order_id'],
			'warehouse_id' => $validated['warehouse_id'],
			'requested_quantities' => $validated['requested_quantities'],
			'received_quantities' => [],
			'status' => 'Requested',
			'notes' => $validated['notes'] ?? null,
		]);

        return redirect()->route('outbound_requests.index')->with('success', 'Outbound request created.');
    }

	public function show($id){
		$outboundRequest = OutboundRequest::with('sales', 'warehouse', 'expedition')->findOrFail($id);
		$expedition = $outboundRequest->expedition;
		$outboundRequestLocations = [];
		foreach($outboundRequest->requested_quantities as $productId => $quantity){
			$outboundRequestLocations[$productId] = OutboundRequestLocation::join('locations', 'outbound_request_locations.location_id', '=', 'locations.id')
			->where('outbound_request_locations.product_id', $productId)
			->select('locations.id', 'locations.room', 'locations.rack', 'outbound_request_locations.quantity')
			->get();
		}

		return view('outbound_requests.show', compact('outboundRequest', 'expedition', 'outboundRequestLocations'));
	}

	public function edit($id)
	{

		$outboundRequest = OutboundRequest::with('sales', 'warehouse')->findOrFail($id);
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
		$outboundRequest = OutboundRequest::findOrFail($id);
		
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

	public function rejectRequest(OutboundRequest $outboundRequest)
	{
		$sales = $outboundRequest->sales;
		
		// go back status
		$sales->admin_notes .= $outboundRequest->notes;
		$sales->status = 'Planned';
		$sales->save();
		
		// destroy outbound
		$outboundRequest->delete();
		
		return redirect()->route('inventory.index')
						 ->with('success', 'Outbound Request has been rejected & deleted successfully.');
	}

	
	public function updateStatus(OutboundRequest $outboundRequest, $status)
    {
		DB::transaction(function () use ($outboundRequest, $status) {
			$outboundRequestLocations = OutboundRequestLocation::where('outbound_request_id', $outboundRequest->id)->get();

			foreach ($outboundRequestLocations as $location) {
				$inventory = Inventory::where('product_id', $location->product_id)
					->where('warehouse_id', $outboundRequest->warehouse_id)
					->where('location_id', $location->location_id)
					->first();
	
				// Handle inventory updates for each status
				if ($status === 'Pending Confirmation') {
					// Reserve stock
					$inventory->reserved_quantity += $location->quantity;
					$inventory->quantity -= $location->quantity;
				} elseif ($status === 'In Transit') {
					// Move from reserved to in transit
					$inventory->reserved_quantity -= $location->quantity;
					$inventory->in_transit_quantity += $location->quantity;
				} elseif ($status === 'Rejected' || $status === 'Cancelled') {
					// Restore reserved stock
					$inventory->reserved_quantity -= $location->quantity;
					$inventory->quantity += $location->quantity;
				}
	
				$inventory->save();
			}
	
			// Update the outbound request status
			$outboundRequest->status = $status;
	
			// Existing validation and updates for 'In Transit' status
			if ($status === 'In Transit') {
				$validated = request()->validate([
					'tracking_number' => 'required|string',
					'real_shipping_fee' => 'required|numeric|min:0',
				]);
				$outboundRequest->update(array_merge($validated, ['status' => $status]));
			} else {
				$outboundRequest->save();
			}
		});

		$this->updateSalesStatus($outboundRequest->sales_order_id);

		return response()->json(['success' => true]);
    }

	
	
	public function checkStockAvailability(OutboundRequest $outboundRequest, $validated, Request $request)
	{
		// Handle deleted locations
		if (!empty($request->input('deleted_locations'))) {
			$deletedLocationIds = explode(',', rtrim($request->input('deleted_locations'), ','));
			OutboundRequestLocation::where('outbound_request_id', $outboundRequest->id)
				->whereIn('location_id', $deletedLocationIds)
				->delete();
		}
	
		// Save or update locations
		if (isset($validated['locations'])) {
			foreach($outboundRequest->requested_quantities as $productId => $quantity){
				if(isset($validated['locations'][$productId]) && !empty($validated['locations'][$productId])) {
					$locations = $validated['locations'][$productId];
					$totalQuantity = 0;

					$locationIds = [];
					foreach ($locations as $locationData) {
						if (in_array($locationData['location_id'], $locationIds)) {
							continue;
						}
						$locationIds[] = $locationData['location_id'];
						
						if (isset($locationData['location_id']) && !empty($locationData['location_id'])) {
							$qty_in_location = Inventory::where('warehouse_id', $outboundRequest->warehouse_id)
								->where('product_id', $productId)
								->where('location_id', $locationData['location_id'])
								->sum('quantity');
								
								// validate if qty in location is more than requested qty
								if ($qty_in_location < $locationData['quantity']) {
									return back()->withErrors(['error' => 'Quantity in location is less than requested quantity for product ID ' . $productId]);
								}

							$totalQuantity += $locationData['quantity'];
							
							$outboundRequestLocation = OutboundRequestLocation::updateOrCreate(
								[
									'outbound_request_id' => $outboundRequest->id,
									'product_id' => $productId,
									'location_id' => $locationData['location_id'],
								],
								[
									'quantity' => $locationData['quantity'],
									]
								);
						}
					}
				} else {
					return back()->withErrors(['error' => 'No locations provided for product ID ' . $productId]);
				}
				
				// validate if total quantity is more than requested qty
				if ($totalQuantity != $quantity) {
					return back()->withErrors(['error' => 'Total quantity is not match with requested quantity for product ID ' . $productId]);
				}	
			}

			// If stock is sufficient, proceed to the next status
			$this->updateStatus($outboundRequest, 'Pending Confirmation');
	
			return redirect()->route('outbound_requests.index', $outboundRequest->id)
							 ->with('success', 'Stock verified and status updated to Pending Confirmation.');
		} else {
			return back()->withErrors(['error' => 'No locations provided.']);
		}

	}



	// Helper function to update sales status based on outbound requests
	private function updateSalesStatus($salesId)
	{
		$sales = Sale::with('outboundRequests')->findOrFail($salesId);
		$outboundRequests = $sales->outboundRequests;

		if ($outboundRequests->every(fn($or) => $or->status === 'Completed')) {
			// All outbound requests are completed
			$sales->status = 'Completed';
		} elseif ($outboundRequests->contains('status', 'Customer Complaint')) {
			// If any request has a customer complaint
			$sales->status = 'Customer Complaint';
		} elseif ($outboundRequests->contains('status', 'In Transit') && $outboundRequests->contains('status', 'Completed')) {
			// There’s at least one completed request, but some are still in transit
			$sales->status = 'Customer Complaint';
		} elseif ($outboundRequests->every(fn($or) => $or->status === 'Ready to Complete')) {
			// All outbound requests are ready to complete
			$sales->status = 'Ready to Complete';
		} else {
			// Default to "Planned" if none of the above conditions are met
			$sales->status = 'In Transit';
		}

		$sales->save();
	}


	public function complete($id)
	{
		$outboundRequest = OutboundRequest::findOrFail($id);

		if ($outboundRequest->status !== 'Ready to Complete') {
			return redirect()->route('outbound_requests.show', $id)
							->withErrors(['error' => 'Outbound request is not ready for completion.']);
		}

		// Pass the outbound request to the view for completion verification
		return view('outbound_requests.complete', compact('outboundRequest'));
	}



	public function verifyCompletion($id, Request $request)
	{
		$outboundRequest = OutboundRequest::findOrFail($id);

		if ($outboundRequest->status !== 'Ready to Complete') {
			return redirect()->route('outbound_requests.show', $id)
				->withErrors(['error' => 'Outbound request is not ready for completion.']);
		}

		// Validate received quantities
		$validated = $request->validate([
			'received_quantities' => 'required|array',
			'received_quantities.*' => 'required|integer|min:0',
		]);

		$receivedQuantities = $validated['received_quantities'];

		foreach ($receivedQuantities as $productId => $receivedQuantity) {
			$remainingQty = $receivedQuantity;

			$outboundRequestLocations = OutboundRequestLocation::where('outbound_request_id', $outboundRequest->id)
									->where('product_id', $productId)
									->get();
			foreach ($outboundRequestLocations as $location) {
				if ($location->product_id !== $productId || $remainingQty <= 0) {
					continue;
				}

				// Deduct stock from inventory
				$inventory = Inventory::where('product_id', $productId)
					->where('warehouse_id', $outboundRequest->warehouse_id)
					->where('location_id', $location->location_id)
					->first();

				$deductQty = min($location->quantity, $remainingQty);
				$remainingQty -= $deductQty;

				if ($inventory) {
					$inventory->in_transit_quantity -= $deductQty;
					$inventory->save();

					// Log inventory history
					InventoryHistory::create([
						'product_id' => $productId,
						'warehouse_id' => $outboundRequest->warehouse_id,
						'location_id' => $location->location_id,
						'quantity' => -1 * $deductQty,
						'transaction_type' => 'Outbound',
						'notes' => 'Completed Outbound Request ID: ' . $outboundRequest->id,
					]);
				}
			}
		}

		$outboundRequest->status = 'Completed';
		$outboundRequest->save();

		return redirect()->route('outbound_requests.show', $outboundRequest->id)
			->with('success', 'Outbound request completed successfully and inventory updated.');
	}


}
