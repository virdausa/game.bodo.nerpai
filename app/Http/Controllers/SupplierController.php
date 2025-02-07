<?php

namespace App\Http\Controllers;

use App\Http\Requests\Supplier\CreateRequest;
use App\Models\Supplier;
use App\Services\SupplierService;
use App\Http\Requests\Supplier\IndexRequest;
use App\Http\Requests\Supplier\ShowRequest;
use App\Http\Requests\Supplier\StoreRequest;
use App\Http\Requests\Supplier\UpdateRequest;
use App\Http\Requests\Supplier\DestroyRequest;
use App\Http\Requests\Supplier\EditRequest;

class SupplierController extends Controller
{
    protected Supplier $supplier;
    protected SupplierService $supplierService;

    public function __construct(Supplier $supplier, SupplierService $supplierService)
    {
        $this->supplier = $supplier;
        $this->supplierService = $supplierService;
    }

    public function index(IndexRequest $request)
    {
        $suppliers = $this->supplierService->getAll();
        return view('suppliers.index', compact('suppliers'));
    }

    public function show(ShowRequest $request, Supplier $supplier)
    {
        $supplier = $this->supplierService->getOne($supplier);
        return view('suppliers.show', compact('supplier'));
    }

    public function create(CreateRequest $request)
    {
        return view('suppliers.create');
    }

    public function store(StoreRequest $request)
    {
        $this->supplierService->store($request->validated());
        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function edit(EditRequest $request, Supplier $supplier)
    {
        $supplier = $this->supplierService->getOne($supplier);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(UpdateRequest $request, Supplier $supplier)
    {
        $supplier = $this->supplierService->update($request->validated(), $supplier);
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(DestroyRequest $request, Supplier $supplier)
    {
        $this->supplierService->destroy($supplier);
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
