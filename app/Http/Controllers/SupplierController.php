<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Services\SupplierService;
use App\Http\Requests\Supplier\IndexRequest;
use App\Http\Requests\Supplier\ShowRequest;
use App\Http\Requests\Supplier\StoreRequest;
use App\Http\Requests\Supplier\UpdateRequest;
use App\Http\Requests\Supplier\DestroyRequest;
use App\Http\Resources\SupplierCollection;
use App\Http\Resources\SupplierResource;

class SupplierController extends Controller
{
    protected Supplier $supplier;
    protected SupplierService $supplierService;

    public function __construct(Supplier $supplier, SupplierService $supplierService)
    {
        $this->supplier = $supplier;
        $this->supplierService = $supplierService;
    }

    public function index(IndexRequest $request): SupplierCollection
    {
        return $this->supplierService->index();
    }

    public function show(ShowRequest $request, Supplier $supplier): SupplierResource
    {
        return $this->supplierService->show($supplier);
    }

    public function store(StoreRequest $request): SupplierResource
    {
        return $this->supplierService->store($request->validated());
    }

    public function update(UpdateRequest $request, Supplier $supplier): SupplierResource
    {
        return $this->supplierService->update($request->validated(), $supplier);
    }

    public function destroy(DestroyRequest $request, Supplier $supplier): void
    {
        $this->supplierService->destroy($supplier);
    }
}
