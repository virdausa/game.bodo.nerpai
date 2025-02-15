<?php

namespace App\Services;

use App\Http\Resources\SupplierCollection;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;

class SupplierService
{
    protected Supplier $supplier;

    public function __construct(Supplier $supplier)
    {
        $this->supplier = $supplier;
    }

    public function getAll(): SupplierCollection
    {
        return new SupplierCollection($this->supplier->getSuppliers());
    }

    public function getOne(String $id): SupplierResource
    {
        $supplier = $this->supplier->getSupplier($id);
        return new SupplierResource($supplier);
    }

    public function store(array $data): SupplierResource
    {
        return new SupplierResource($this->supplier->createSupplier($data));
    }

    public function update(array $data, String $id): SupplierResource
    {
        $supplier = $this->supplier->getSupplier($id);
        return new SupplierResource($supplier->updateSupplier($data, $supplier));
    }

    public function destroy(String $id): void
    {
        $supplier = $this->supplier->getSupplier($id);
        $this->supplier->deleteSupplier($supplier);
    }
}
