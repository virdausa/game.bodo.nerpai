<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Services\SupplierService;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;

class SupplierController extends Controller
{
    protected Supplier $supplier;
    protected SupplierService $supplierService;

    public function __construct(Supplier $supplier, SupplierService $supplierService)
    {
        $this->supplier = $supplier;
        $this->supplierService = $supplierService;
    }

    public function index()
    {
        $suppliers = $this->supplierService->getAll();
        return view('suppliers.index', compact('suppliers'));
    }

    public function show(String $id)
    {
        $supplier = $this->supplierService->getOne($id);
        return view('suppliers.show', compact('supplier'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(StoreSupplierRequest $request)
    {
        $this->supplierService->store($request->validated());
        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function edit(String $id)
    {
        $supplier = $this->supplierService->getOne($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(UpdateSupplierRequest $request, String $id)
    {
        $this->supplierService->update($request->validated(), $id);
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(String $id)
    {
        $this->supplierService->destroy($id);
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
