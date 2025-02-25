<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Models\Company\WarehouseLocation;
use App\Models\Company\Warehouse;

use App\Http\Controllers\Controller;

class WarehouseLocationController extends Controller
{
    public function index()
	{
		$warehouses = Warehouse::with('warehouse_locations')->get();
		return view('warehouse_locations.index', compact('warehouses'));
	}

	public function create()
	{
		$warehouses = Warehouse::with('warehouse_locations')->get();
		$existingRooms = WarehouseLocation::select('room')->distinct()->pluck('room');
		return view('warehouse_locations.create', compact('warehouses', 'existingRooms'));
	}

	public function store(Request $request)
	{
		$request->validate([
			'warehouse_id' => 'required|exists:warehouses,id',
			'rack' => 'required|string',
			'existing_room' => 'nullable|string',
			'new_room' => 'nullable|string',
		]);

		// Choose the room based on existing or new input
		$room = $request->input('new_room') ?: $request->input('existing_room');
		if (!$room) {
			return back()->withErrors('Please select an existing room or enter a new room.');
		}

		// Validate that rack is unique within the room
		$exists = WarehouseLocation::where('warehouse_id', $request->warehouse_id)
						  ->where('room', $room)
						  ->where('rack', $request->rack)
						  ->exists();

		if ($exists) {
			return back()->withErrors('The rack in this room already exists.');
		}

		// Create WarehouseLocation
		WarehouseLocation::create([
			'warehouse_id' => $request->warehouse_id,
			'room' => $room,
			'rack' => $request->rack,
		]);

		return redirect()->route('warehouse_locations.index')->with('success', 'WarehouseLocation added successfully.');
	}


	public function edit($id)
	{
		$location = WarehouseLocation::findOrFail($id);
		$warehouses = Warehouse::with('warehouse_locations')->get();
		// Get unique rooms for the selected warehouse
		$existingRooms = WarehouseLocation::where('warehouse_id', $location->warehouse_id)->select('room')->distinct()->pluck('room');
		return view('warehouse_locations.edit', compact('location', 'warehouses', 'existingRooms'));
	}


	public function update(Request $request, $id)
	{
		$location = WarehouseLocation::findOrFail($id);

		$request->validate([
			'warehouse_id' => 'required|exists:warehouses,id',
			'rack' => 'required|string',
			'existing_room' => 'nullable|string',
			'new_room' => 'nullable|string',
		]);

		// Choose the room based on existing or new input
		$room = $request->input('new_room') ?: $request->input('existing_room');
		if (!$room) {
			return back()->withErrors('Please select an existing room or enter a new room.');
		}

		// Validate that rack is unique within the room (excluding the current location being updated)
		$exists = WarehouseLocation::where('warehouse_id', $request->warehouse_id)
						  ->where('room', $room)
						  ->where('rack', $request->rack)
						  ->where('id', '!=', $id)
						  ->exists();

		if ($exists) {
			return back()->withErrors('The rack in this room already exists.');
		}

		// Update location
		$location->update([
			'warehouse_id' => $request->warehouse_id,
			'room' => $room,
			'rack' => $request->rack,
		]);

		return redirect()->route('warehouse_locations.index')->with('success', 'WarehouseLocation updated successfully.');
	}

	
	public function destroy($id)
	{
		$location = WarehouseLocation::findOrFail($id);
		$location->delete();

		return redirect()->route('warehouse_locations.index')->with('success', 'WarehouseLocation deleted successfully.');
	}

}
