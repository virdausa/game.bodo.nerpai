<x-company-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h3 class="text-lg font-bold dark:text-white">Edit Purchase</h3>
                    <p class="text-sm dark:text-gray-200 mb-3">Update the details of your purchase.</p>

                    <div class="p-2 border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-600 mb-4">
                        <form action="{{ route('purchases.update', $purchase->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <x-input-label for="supplier_id">Supplier</x-input-label>
                                <select name="supplier_id" id="supplier_id" class="bg-gray-100 w-full px-4 py-2 bg-gray-100 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" required>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <x-input-label for="po_date">Purchase Date</x-input-label>
                                <input type="date" name="po_date" class="bg-gray-100 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" value="{{ ($purchase->po_date)->format('Y-m-d') }}" >
                            </div>

                            <div class="mb-4">
                                <x-input-label for="warehouse_id">Select Warehouse</x-input-label>
                                <select name="warehouse_id" id="warehouse_id" class="bg-gray-100 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" >
                                    @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ $purchase->warehouse_id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <x-input-label for="status">Purchase Status</x-input-label>
                                <input type="text" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" name="status" value="{{ $purchase->status }}" readonly>
                            </div>

                            <div class="mb-4">
                                <x-input-label for="supplier_notes">Supplier Notes</x-input-label>
                                <textarea name="supplier_notes" class="bg-gray-100 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" {{ $purchase->status == 'Completed' ? 'readonly' : '' }}>{{ $purchase->notes }}</textarea>
                            </div>

                            <div class="mb-4">
                                <x-input-label for="admin_notes">Admin Notes</x-input-label>
                                <textarea name="admin_notes" class="bg-gray-100 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" {{ $purchase->status == 'Completed' ? 'readonly' : '' }}>{{ $purchase->notes }}</textarea>
                            </div>

                            <h3 class="text-lg font-bold mt-6">Products</h3>
                            <div id="product-selection" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                @foreach ($purchase->products as $index => $product)
                                <div class="product-item mb-4 p-4 border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-600">
                                <div class="flex inline justify-between space items-center">
										<h3 class="text-md font-bold">Products</h3>
										<button type="button"
											class="ml-3 bg-red-500 text-sm text-white px-4 py-1 rounded-md hover:bg-red-700 remove-product">
											Remove
										</button>
									</div>
									<div class="mb-3 mt-1 flex-grow border-t border-gray-500 dark:border-gray-700">
									</div>    
                                <x-input-label for="product_id">Select Product</x-input-label>
                                    <select name="products[{{ $index }}][product_id]" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white"  required>
                                        @foreach ($products as $availableProduct)
                                        <option value="{{ $availableProduct->id }}" {{ $availableProduct->id == $product->id ? 'selected' : '' }}>{{ $availableProduct->name }} - Rp{{ $availableProduct->price }}</option>
                                        @endforeach
                                    </select>

                                    <x-input-label for="quantity">Quantity</x-input-label>
                                    <input type="number" name="products[{{ $index }}][quantity]" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" value="{{ $product->pivot->quantity }}" required >

                                    <x-input-label for="buying_price">Buying Price</x-input-label>
                                    <input type="number" name="products[{{ $index }}][buying_price]" step="0.01" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" value="{{ $product->pivot->buying_price }}" required >
                                </div>
                                @endforeach
                            </div>
							<!-- <x-button type="button" id="add-product" class="mr-3" >Add Another Product</x-button> -->
                            <button class="m-4 px-3 py-2 inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90" type="button" id="add-product" class="mr-3">
                                Add Another Product
                            </button>

                            <div class="my-6 flex-grow border-t border-gray-500 dark:border-gray-700"></div>
                            <div class="m-4">
                                <a href="{{ route('purchases.index') }}">
                                    <x-secondary-button type="button">Cancel</x-secondary-button>
                                </a>
                                <x-primary-button>Update Purchase</x-primary-button>
                            </div>
                        </form>

                        <div class="my-6 flex-grow border-t border-gray-500 dark:border-gray-700"></div>
                        @if ($purchase->status == 'PO_REQUEST_TO_SUPPLIER')
                        <div class="flex justify-end m-4">
                            <form action="{{ route('purchases.action', ['purchases' => $purchase->id, 'action' => 'PO_CONFIRMED']) }}" method="POST">
                                @csrf
                                @method('POST')
                                <x-primary-button type="submit">Input Invoice Pembelian dari Supplier</x-primary-button>
                            </form>
                        </div>
                        @endif

                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                const productSelection = document.getElementById('product-selection');
                                let productIndex = {{ $purchase->products->count() }};
								

                                document.getElementById('add-product').addEventListener('click', function () {
                                    const newProductDiv = document.createElement('div');
                                    newProductDiv.classList.add('product-item', 'mb-4', 'p-4', 'border', 'border-gray-200', 'rounded-lg', 'shadow-md', 'dark:bg-gray-800', 'dark:border-gray-600');
                                    newProductDiv.innerHTML = `
                                    <div class="flex inline justify-between space items-center">
										<h3 class="text-md font-bold">Products 1</h3>
										<button type="button"
											class="ml-3 bg-red-500 text-sm text-white px-4 py-1 rounded-md hover:bg-red-700 remove-product">
											Remove
										</button>
									</div>
									<div class="mb-3 mt-1 flex-grow border-t border-gray-500 dark:border-gray-700">
									</div>
                                        <x-input-label for="product_id">Select Product</x-input-label>
                                        <select name="products[${productIndex}][product_id]" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" required>
                                            @foreach ($products as $availableProduct)
                                                <option value="{{ $availableProduct->id }}">{{ $availableProduct->name }} - Rp{{ $availableProduct->price }}</option>
                                            @endforeach
                                        </select>

                                        <x-input-label for="quantity">Quantity</x-input-label>
                                        <input type="number" name="products[${productIndex}][quantity]" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" min="1" required>

                                        <x-input-label for="buying_price">Buying Price</x-input-label>
                                        <input type="number" name="products[${productIndex}][buying_price]" step="0.01" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" required>
                                    `;

                                    productSelection.appendChild(newProductDiv);
                                    productIndex++;
                                });
                                productSelection.addEventListener('click', function (event) {
                                    if (event.target && event.target.classList.contains('remove-product')) {
                                        const productDiv = event.target.closest('.product-item');
                                        productDiv.remove(); // Remove the product div
                                    }
                                });
                            });
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-company-layout>