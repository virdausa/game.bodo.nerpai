<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    public function index()
	{
		$warehouses = Warehouse::all();
		return view('warehouses.index', compact('warehouses'));
	}

	public function create()
	{
		return view('warehouses.create');
	}

	public function store(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'address' => 'nullable|string|max:255',
		]);

		Warehouse::create($request->all());

		return redirect()->route('warehouses.index')->with('success', 'Warehouse created successfully.');
	}

	public function show(string $id)
	{
		$warehouse = Warehouse::with('locations')->findOrFail($id);
		return view('warehouses.show', compact('warehouse'));
	}

	public function edit(string $id)
	{
		$warehouse = Warehouse::findOrFail($id);
		return view('warehouses.edit', compact('warehouse'));
	}

	public function update(Request $request, string $id)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'address' => 'nullable|string|max:255',
		]);

		$warehouse = Warehouse::findOrFail($id);

		$warehouse->update($request->all());

		return redirect()->route('warehouses.index')->with('success', "Warehouse {$warehouse->name} updated successfully.");
	}

	public function destroy(string $id)
	{
		$warehouse = Warehouse::findOrFail($id);

		$warehouse->delete();

		return redirect()->route('warehouses.index')->with('success', "Warehouse {$warehouse->name} deleted successfully.");
	}

}
