<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreEmployee;
use App\Models\Employee;
use Illuminate\Support\Facades\Session;

class StoreEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $store_id = Session::get('company_store_id');
        $store_employees = StoreEmployee::where('store_id', $store_id)->get();
        $employees_without_store = Employee::all()
                            ->whereNotIn('id', $store_employees->pluck('employee_id'));
        return view('store_employees.index', compact('store_employees', 'employees_without_store'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('store_employees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $store_employee = StoreEmployee::create([
            'store_id' => Session::get('company_store_id'),
            'employee_id' => $validated['employee_id'],
            'role' => 'guest',
            'status' => 'active',
        ]);

        return redirect()->route('store_employees.index')->with('success', "{$store_employee->employee->companyuser->user->name} assigned to be Store employee successfully!");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $store_employee = StoreEmployee::find($id);

        $store_employee->delete();

        return redirect()->route('store_employees.index')->with('success', "Store employee {$store_employee->employee->companyuser->user->name} removed successfully!");
    }
}
