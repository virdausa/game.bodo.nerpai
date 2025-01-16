<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-white">
                <div class="p-6 text-gray-900 dark:text-white"></div>
                <h1>Create Outbound Request</h1>
                <form action="{{ route('outbound-requests.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <x-input-label for="sales_order_id">Sales Order ID</x-input-label>
                        <x-text-input type="text" name="sales_order_id" class="form-control" required
                            placeholder="Enter sales order ID"/>
                    </div>
                    <div class="mb-4">
                        <x-input-label for="warehouse_id">Warehouse</x-input-label>
                        <select name="warehouse_id"
                            class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white">
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 mt-3 flex-grow border-t border-gray-500 dark:border-gray-700"></div>

                    <h3 class="text-lg font-bold dark:text-white">Product Overview</h3>

                    <div id="product-selection" class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        <div
                            class="product-item mb-4 p-4 border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-600">
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
                            <select name="products[0][product_id]"
                                class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} -
                                        Rp{{ $product->price }}</option>
                                @endforeach
                            </select>

                            <x-input-label for="quantity">Quantity</x-input-label>
                            <input type="number" name="products[0][quantity]"
                                class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white"
                                min="1" required>
                        </div>
                    </div>
                    <x-button2 type="button" id="add-product" class="mr-3">Add Another Product</x-button>
                        <x-primary-button>Create Outbound Request</x-primary-button>
                </form>

                <script>
							document.addEventListener('DOMContentLoaded', () => {
								const productSelection = document.getElementById('product-selection');
								let productIndex = 1;
								let identifier = 2;


								document.getElementById('add-product').addEventListener('click', function () {
									const newProductDiv = document.createElement('div');
									newProductDiv.classList.add('product-item', 'mb-4', 'p-4', 'border', 'border-gray-200', 'rounded-lg', 'shadow-md', 'dark:bg-gray-800', 'dark:border-gray-600');
									newProductDiv.innerHTML = `
									<div class="flex inline justify-between space items-center">
										<h3 class="text-md font-bold">Products ${identifier}</h3>
										<button type="button"
											class="ml-3 bg-red-500 text-sm text-white px-4 py-1 rounded-md hover:bg-red-700 remove-product">
											Remove
										</button>
									</div>
									<div class="mb-3 mt-1 flex-grow border-t border-gray-500 dark:border-gray-700">
									</div>
									<x-input-label for="product_id">Select Product</x-input-label>
									<select name="products[${productIndex}][product_id]" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white">
										@foreach ($products as $product)
											<option value="{{ $product->id }}">{{ $product->name }} - Rp{{ $product->price }}</option>
										@endforeach
									</select>

									<x-input-label for="quantity">Quantity</x-input-label>
									<input type="number" name="products[${productIndex}][quantity]" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" min="1" required>

									
								`;
									productSelection.appendChild(newProductDiv);
									productIndex++;
									identifier++;

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
</x-app-layout>