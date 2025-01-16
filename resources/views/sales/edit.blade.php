<x-app-layout>
<div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
    <h3 class="text-lg font-bold dark:text-white">Edit Sales Order</h3>
    <div class="my-6 flex-grow border-t border-gray-300 dark:border-gray-700"></div>

    <form action="{{ route('sales.update', $sale->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <x-input-label for="customer_id">Select Customer</x-input-label>
            <select name="customer_id" id="customer_id" class="bg-gray-100 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" required>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-4">
            <x-input-label for="sale_date">Sale Date</x-input-label>
            <input type="date" name="sale_date" class="bg-gray-100 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" value="{{ $sale->sale_date }}" required {{ $sale->status != 'Planned' ? 'readonly' : '' }}>
        </div>
        
        <div class="mb-4">
            <label for="warehouse_id">Select Warehouse</label>
            <select name="warehouse_id" class="bg-gray-100 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white {{ $sale->status != 'Planned' ? 'readonly-select' : '' }}" required {{ $sale->status != 'Planned' ? 'readonly' : '' }}>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" {{ $sale->warehouse_id == $warehouse->id ? 'selected' : '' }}>
                        {{ $warehouse->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="my-6 flex-grow border-t border-gray-300 dark:border-gray-700"></div>

        <h3 class="text-lg font-bold dark:text-white mb-3">Manage Products</h3>
        <div id="product-selection" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                @foreach ($sale->products as $index => $product)
                                <div class="bg-gray-50 product-item mb-4 p-4 border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-600">
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
                                    <select name="products[{{ $index }}][product_id]" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" {{ $sale->status != 'Planned' ? 'readonly' : '' }} required>
                                        @foreach ($products as $availableProduct)
                                        <option value="{{ $availableProduct->id }}" {{ $availableProduct->id == $product->id ? 'selected' : '' }}>{{ $availableProduct->name }} - Rp{{ $availableProduct->price }}</option>
                                        @endforeach
                                    </select>

                                    <x-input-label for="quantity">Quantity</x-input-label>
                                    <input type="number" name="products[{{ $index }}][quantity]" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" value="{{ $product->pivot->quantity }}" required {{ $sale->status != 'Planned' ? 'readonly' : '' }}>

                                    <x-input-label for="price">Price</x-input-label>
                                    <input type="number" name="products[{{ $index }}][price]" step="0.01" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" value="{{ $product->pivot->price }}" required {{ $sale->status != 'Planned' ? 'readonly' : '' }}>
                                    
                                    <x-input-label for="sales_note">Note</x-input-label>
                                    <x-input-textarea name="products[{{ $index }}][note]" class="form-control" value="{{ $product->pivot->quantity }}"></x-input-textarea>
                                </div>
                                @endforeach
                            </div>

                            <x-button2 type="button" id="add-product" class="mr-3 bg-blue-700">Add Another Product</x-button2>
                            <div class="my-6 flex-grow border-t border-gray-300 dark:border-gray-700"></div>
                            <h3 class="text-lg font-bold dark:text-white mb-3">Expedition</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
        <div class="form-group">
            <x-input-label for="expedition_id">Expedition</x-input-label>
            <select name="expedition_id" class="bg-gray-100 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white{{ $sale->status != 'Planned' ? 'readonly-select' : '' }}" required {{ $sale->status != 'Planned' ? 'readonly' : '' }}>
                @foreach($expeditions as $expedition)
                    <option value="{{ $expedition->id }}" {{ $sale->expedition_id == $expedition->id ? 'selected' : '' }}>
                        {{ $expedition->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <x-input-label for="estimated_shipping_fee">Estimated Shipping Fee</x-input-label>
            <input type="number" name="estimated_shipping_fee" class="bg-gray-100 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" value="{{ $sale->estimated_shipping_fee ?? 0 }}" required {{ $sale->status != 'Planned' ? 'readonly' : '' }}>
        </div>

        <div class="form-group">
            <x-input-label for="complaint_details">Complaint Details (if any)</x-input-label>
            <x-input-textarea name="complaint_details" class="bg-gray-100 form-control">{{ $sale->complaint_details }}</x-input-textarea>
        </div>
        </div>
        <div class="my-6 flex-grow border-t border-gray-300 dark:border-gray-700"></div>

		<!-- Status Display -->
		<h3 class="text-lg font-bold dark:text-white mb-3">Status Display</h3>
        <div class="form-group">
            <x-input-label for="status">Sales Status</x-input-label>
            <input type="text" class="mb-4 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" name="status" value="{{ $sale->status }}" readonly>
        </div>

        @if ($sale->status != 'Planned' && $sale->status != 'Unpaid')
            @if (count($outboundRequests) > 0)
                @foreach($outboundRequests as $outboundRequest)
                    <div class="form-group">
                        <x-input-label for="status_outbound">Outbound Status</x-input-label>
                        <input type="text" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" value="{{ $outboundRequest->status }}" readonly>
                    </div>

                    <!-- Customer Received Quantities Section -->
                    <h3>Customer Received Quantities</h3>
                    <x-table-table class="table table-bordered">
                        <x-table-thead>
                            <tr>
                                <x-table-th>Product</x-table-th>
                                <x-table-th>Shipped Quantity</x-table-th>
                                <x-table-th>Received Quantity</x-table-th>
                            </tr>
                        </x-table-thead>
                        <tbody>
                            @foreach ($outboundRequest->requested_quantities as $productId => $quantity)
                                <x-table-tr>
                                    <x-table-td>{{ $outboundRequest->sales->products->find($productId)->name }}</x-table-td>
                                    <x-table-td>{{ $quantity }}</x-table-td>
                                    <x-table-td>
                                        <input type="number" name="received_quantities[{{ $outboundRequest->id }}][{{ $productId }}]" 
                                            value="{{ $outboundRequest->received_quantities[$productId] ?? 0 }}" 
                                            class="form-control" min="0" {{ $outboundRequest->status != 'In Transit' ? 'readonly' : '' }}>
                                    </x-table-td>
                                </x-table-tr>
                            @endforeach
                        </tbody>
                    </x-table-table>
                    @if ($outboundRequest->status == 'In Transit')
                        <button name="submit" type="submit" class="btn btn-primary" value="Update Received Quantities">Update Received Quantities</button>
                    @endif
                @endforeach
            @endif
		@endif

        <!-- Action Buttons -->
        <x-input-label for="actions">Actions</x-input-label>
        <div class="mt-3">
            @switch($sale->status)
                @case('Planned')
                    <a href="{{ route('sales.updateStatus', ['sale' => $sale->id, 'status' => 'Unpaid']) }}" class="btn btn-primary mb-3">Request Outbound</a>
                    @break

                @case('Unpaid')
                    @if($outboundRequests[count($outboundRequests)-1]->status == 'Pending Confirmation')
                        <a href="{{ route('sales.updateStatus', ['sale' => $sale->id, 'status' => 'Pending Shipment']) }}" class="btn btn-success mb-3">Mark as Paid</a>
                    @else
                        <h4>Waiting Outbound Request Confirmation before can be Paid</h4>
                    @endif
                    @break

                @case('In Transit')
                    @break

                @case('Customer Complaint')
                    <a href="{{ route('sales.updateStatus', ['sale' => $sale->id, 'status' => 'Completed']) }}" class="btn btn-success mb-3">Resolve Complaint & Complete</a>
                    @break

                @case('Received - Pending Verification')
                    <a href="{{ route('sales.updateStatus', ['sale' => $sale->id, 'status' => 'Completed']) }}" class="btn btn-success mb-3">Complete Order</a>
                    @break
            @endswitch
        </div>
        <div class="mb-3 mt-1 flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        <div class="flex justify-end space-x-4">
        <x-button href="{{route('sales.index')}}"
                            class="border rounded border-gray-400 dark:border-gray-700 p-2 text-lg hover:underline text-gray-700 dark:text-gray-400">Cancel</x-button>    
        <x-primary-button type="submit">Update Sale</x-primary-button>
            
        </div>
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

									<x-input-label for="price">Price</x-input-label>
									<input type="number" name="products[${productIndex}][price]" step="0.01" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" required>
								 <x-input-label for="sales_note">Note</x-input-label>
                                        <x-input-textarea name="products[${productIndex}][note]" class="form-control"></x-input-textarea>
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
    // JavaScript to prevent selection changes on readonly-select elements
    document.querySelectorAll('.readonly-select').forEach(function(select) {
        select.addEventListener('mousedown', function(event) {
            event.preventDefault();
        });
        select.addEventListener('click', function(event) {
            event.preventDefault();
        });
        select.addEventListener('change', function(event) {
            event.preventDefault();
        });
    });
	</script>
     </div> </div> </div> </div>
</x-app-layout>
