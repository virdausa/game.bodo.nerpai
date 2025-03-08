<x-company-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <div class="p-6 text-gray-900 dark:text-white">
                        <h3 class="text-lg dark:text-white font-bold">Add Sales Data</h3>
                        <div class="my-6 flex-grow border-t border-gray-300 dark:border-gray-700"></div>

                        <form action="{{ route('sales.store') }}" method="POST">
                            @csrf

                            <!-- Select Consignee Type -->
                            <div class="form-group mb-4">
                                <x-input-label for="consignee_type" class="block text-sm font-medium text-gray-700">Select Consignee Type</x-input-label>
                                <x-input-select name="consignee_type" id="consignee_type" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
                                    <option value="CUST">Customer</option>
                                    <option value="COMP">Company</option>
                                </x-input-select>
                            </div>
                            <!-- Select Consignee -->
                            <div class="form-group mb-4">
                                <x-input-label for="consignee_id" class="block text-sm font-medium text-gray-700">Select Consignee</x-input-label>
                                <x-input-select name="consignee_id" id="consignee_id" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
                                    <!-- diisi pake js -->
                                </x-input-select>
                            </div>

                            <div class="mb-4">
                                <x-input-label for="date">Sale Date</x-input-label>
                                <input type="date" name="date"
                                    class="bg-gray-100 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white"
                                    required value="<?= date('Y-m-d'); ?>" >
                            </div>

                            <div class="mb-4">
                                <x-input-label for="warehouse_id">Select Warehouse</x-input-label>
                                <select name="warehouse_id"
                                    class="bg-gray-100 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white"
                                    required>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="my-6 flex-grow border-t border-gray-300 dark:border-gray-700"></div>

                            <div id="product-selection" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="">
                                    <div
                                        class="bg-gray-50 product-item mb-4 p-4 border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-600">
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
                                            class="bg-gray-200 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white">
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }} -
                                                    Rp{{ $product->price }}</option>
                                            @endforeach
                                        </select>

                                        <x-input-label for="quantity">Quantity</x-input-label>
                                        <input type="number" name="products[0][quantity]"
                                            class="bg-gray-200 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white"
                                            min="1" required>

                                        <x-input-label for="price">Price</x-input-label>
                                        <input type="number" name="products[0][price]" step="0.01"
                                            class="bg-gray-200 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white"
                                            required>


                                        <x-input-label for="sales_note">Note</x-input-label>
                                        <x-input-textarea name="products[0][note]"
                                            class="bg-gray-200"></x-input-textarea>

                                    </div>
                                </div>
                            </div>


                            <x-button2 type="button" id="add-product" class="mr-3 bg-blue-700">Add Another Product</x-button>
                            
                            <div class="m-4">
                                <a href="{{ route('sales.index') }}">
                                    <x-secondary-button type="button">Cancel</x-secondary-button>
                                </a>
                                <x-primary-button>Create Sale</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const productSelection = document.getElementById('product-selection');
            let productIndex = 1;
            let identifier = 2;


            document.getElementById('add-product').addEventListener('click', function () {
                const newProductDiv = document.createElement('div');
                newProductDiv.classList.add('product-item','bg-gray-50', 'mb-4', 'p-4', 'border', 'border-gray-200', 'rounded-lg', 'shadow-md', 'dark:bg-gray-800', 'dark:border-gray-600');
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
									<select name="products[${productIndex}][product_id]" class="bg-gray-200 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white">
										@foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }} - Rp{{ $product->price }}</option>
                                        @endforeach
									</select>

									<x-input-label for="quantity">Quantity</x-input-label>
									<input type="number" name="products[${productIndex}][quantity]" class="bg-gray-200 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" min="1" required>

									<x-input-label for="price">Price</x-input-label>
									<input type="number" name="products[${productIndex}][price]" step="0.01" class="bg-gray-200 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" required>
								 <x-input-label for="sales_note">Note</x-input-label>
                                        <x-input-textarea name="products[${productIndex}][note]" class="bg-gray-200"></x-input-textarea>
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

    <script>
        let customers = @json($customers);
        let companies = @json($companies);

        function updateConsigneeOptions(selectedConsigneeId = null) {
            let consigneeTypeSelect = document.getElementById('consignee_type').value;
            let consigneeSelect = document.getElementById('consignee_id');
            consigneeSelect.innerHTML = '';

            let data = consigneeTypeSelect == 'CUST' ? customers : companies;
            data.forEach(option => {
                let optionElement = document.createElement('option');
                optionElement.value = option.id;
                optionElement.textContent = option.name;

                if(selectedConsigneeId && selectedConsigneeId == option.id) {
                    optionElement.selected = true;
                }

                consigneeSelect.appendChild(optionElement);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            let existingShipperId = null;
            updateConsigneeOptions(existingShipperId);
        });

        document.getElementById('consignee_type').addEventListener('change', function() {
            updateConsigneeOptions();
        });
    </script>
</x-company-layout>