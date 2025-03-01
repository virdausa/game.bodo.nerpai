<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Models\Company\Warehouse;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Session;

class WarehouseController extends Controller
{
    public function index()
	{
		$warehouses = Warehouse::all();
		return view('company.warehouses.index', compact('warehouses'));
	}

	public function create()
	{
		return view('company.warehouses.create');
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
		$warehouse = Warehouse::with('warehouse_locations')->findOrFail($id);
		return view('company.warehouses.show', compact('warehouse'));
	}

	public function edit(string $id)
	{
		$warehouse = Warehouse::findOrFail($id);
		return view('company.warehouses.edit', compact('warehouse'));
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



	public function switchWarehouse(Request $request, string $id)
	{
		$this->forgetWarehouse();

		$warehouse = Warehouse::findOrFail($id);

		Session::put('company_warehouse_id', $warehouse->id);
		Session::put('company_warehouse_code', $warehouse->code);
		Session::put('company_warehouse_name', $warehouse->name);
		Session::put('layout', 'warehouse');

		return redirect()->route('dashboard-warehouse')->with('success', "Anda masuk ke {$warehouse->name}");
	}


	public function forgetWarehouse()
    {
        foreach(session()->all() as $key => $value) {
            if(str_contains($key, 'warehouse')) {
                session()->forget($key);				
            }
        }
    }


	public function exitWarehouse(Request $request, $route = 'dashboard')
	{
		$this->forgetWarehouse();

		// Redirect ke halaman (atau dashboard company)
		session('layout', 'company');

		return redirect()->route($route)->with('success', 'Anda telah keluar dari warehouse!');
	}
}
