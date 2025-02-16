<?php

namespace App\Services;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductService
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getProducts(): ProductCollection
    {
        return new ProductCollection($this->product->getProducts());
    }

    public function getproduct(String $id): ProductResource
    {
        $product = $this->product->getProduct($id, ['purchases']);
        return new ProductResource($product);
    }

    public function createproduct(array $data): ProductResource
    {
        return new ProductResource($this->product->createProduct($data));
    }

    public function updateProduct(String $id, array $data): ProductResource
    {
        $product = $this->product->getProduct($id);
        return new ProductResource($product->updateProduct($product, $data));
    }

    public function deleteProduct(String $id): void
    {
        $product = $this->product->getProduct($id);
        $this->product->deleteProduct($product);
    }
}
