<x-company-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h1 class="text-2xl font-bold mb-6">Sales Details : {{ $sale->number }}</h1>
                    <div class="mb-3 mt-1 flex-grow border-t border-gray-300 dark:border-gray-700"></div>

                    <h3 class="text-lg font-bold my-3">General Informations</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
                        <div
                            class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-300">SO Number</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $sale->number }}
                            </p>
                        </div>
                        <div
                            class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-300">Sale Date</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $sale->date }}
                            </p>
                        </div>
                        
                        <div
                            class="p-2 bg-gray-50  border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-300">Status</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ ucfirst($sale->status) }}
                            </p>
                        </div>
                        <div
                            class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-300">Customer Name</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $sale->customer->name }}
                            </p>
                        </div>
                        <div
                            class="p-2 bg-gray-50  border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-300">Warehouse</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $sale->warehouse->name }}
                            </p>
                        </div>
                        <div
                            class="p-2 bg-gray-50  border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-300">Admin</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ $sale->employee->companyuser->user->name ?? 'N/A' }}
                            </p>
                        </div>
                        <div
                            class="p-2 bg-gray-50  border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-300">Customer Notes</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ $sale->customer_notes ?? 'N/A' }}
                            </p>
                        </div>
                        <div
                            class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-300">Admin Notes</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ $sale->admin_notes ?? 'N/A' }}
                            </p>
                        </div>


                    </div>

                    <div class="mb-3 mt-1 flex-grow border-t border-gray-300 dark:border-gray-700"></div>
                    <h3 class="text-lg font-bold my-3">Products</h3>
                    <div class="overflow-x-auto mb-6">

                        <x-table-table class="table table-bordered">
                            <x-table-thead>
                                <tr>
                                    <x-table-th>Product</x-table-th>
                                    <x-table-th>Quantity</x-table-th>
                                    <x-table-th>Price</x-table-th>
                                    <x-table-th>Note</x-table-th>
                                </tr>
                            </x-table-thead>
                            <tbody>
                                @foreach($sale->products as $product)
                                    <x-table-tr>
                                        <x-table-td>{{ $product->name }}</x-table-td>
                                        <x-table-td>{{ $product->pivot->quantity }}</x-table-td>
                                        <x-table-td>${{ number_format($product->pivot->price, 2) }}</x-table-td>
                                        <x-table-td>{{ $product->pivot->note ?? 'N/A' }}</x-table-td>
                                    </x-table-tr>
                                @endforeach
                            </tbody>
                        </x-table-table>
                    </div>

                    <div class="mb-3 mt-1 flex-grow border-t border-gray-300 dark:border-gray-700"></div>
                    <h3 class="text-lg font-bold my-3">Shipment Details</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
                        <div
                            class="p-2 bg-gray-50  border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-300">Courier</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ optional($sale->courier)->name ?? 'N/A' }}
                            </p>
                        </div>
                        <div
                            class="p-2 bg-gray-50  border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-300">Estimated Shipping Fee</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                ${{ number_format($sale->estimated_shipping_fee, 2) }}
                            </p>
                        </div>
                        <div
                            class="p-2 bg-gray-50  border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-300">Packing Fee</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                ${{ number_format($sale->packing_fee, 2) }}
                            </p>
                        </div>
                        <div
                            class="p-2 bg-gray-50  border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-300">Real Volume</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ $sale->real_volume ?? 'N/A' }}
                            </p>
                        </div>
                        <div
                            class="p-2 bg-gray-50  border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-300">Real Weight</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ $sale->real_weight ?? 'N/A' }}
                            </p>
                        </div>

                        <div
                            class="p-2 bg-gray-50  border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-300">Real Weight</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ $sale->tracking_number ?? 'N/A' }}
                            </p>
                        </div>

                    </div>
                    <div class="mb-3 mt-1 flex-grow border-t border-gray-300 dark:border-gray-700"></div>
                    <h3 class="text-lg font-bold my-3">Complain Details</h3>
                    <div class="mb-4 flex justify-between items-center">


                        <p
                            class="p-3 bg-gray-50 border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            {{ $sale->complaint_details ?? 'No complaints reported.' }}</p>
                        <x-button-add :route="route('customer_complaints.create', ['sales_order_id' => $sale->id])"
                            text="Add Complaint" />


                    </div>
                    <div class="mb-3 mt-1 flex-grow border-t border-gray-300 dark:border-gray-700"></div>
                    <div class="flex justify-end space-x-4">
                        <x-button href="{{route('sales.index')}}"
                            class="border rounded border-gray-400 dark:border-gray-700 p-3 text-lg hover:underline text-gray-700 dark:text-gray-400">Cancel</x-button>
                        <x-button href="{{route('sales.edit', $sale->id)}}" text="Edit Sales"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 dark:bg-green-700 dark:hover:bg-green-800">Edit Sales</x-button>



                    </div>
                </div>
            </div>
        </div>
</x-company-layout>