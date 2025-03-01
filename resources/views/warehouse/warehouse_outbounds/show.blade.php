<x-company-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h3 class="text-lg font-bold dark:text-white mb-4">Show Outbound Request</h3>
                    <div class="mb-3 mt-1 flex-grow border-t border-gray-300 dark:border-gray-700"></div>

                    <!-- Basic Details -->
                    <x-input-label>General Informations</x-input-label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">

                    <div class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                        <p class="text-sm text-gray-500 dark:text-gray-300">Sales Order</p>
                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100"> {{ $outboundRequest->sales->id }}
                        </p>
                    </div>
                    <div class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                        <p class="text-sm text-gray-500 dark:text-gray-300">Warehouse</p>
                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100"> {{ optional($outboundRequest->warehouse)->name }}
                        </p>
                    </div>
                    <div class="badge badge-{{ $outboundRequest->status == 'Ready to Complete' ? 'success' : 'secondary' }} p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600 ">
                        <p class="text-sm text-gray-500 dark:text-gray-300">Status</p>
                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100"> {{ $outboundRequest->status }}
                        </p>
                    </div>
                    </div>

                    <!-- Requested Quantities -->
                    <div class="mb-3 mt-1 flex-grow border-t border-gray-300 dark:border-gray-700"></div>
                    <x-input-label>Requested Quantities</x-input-label>
                    <div class="overflow-x-auto mb-6">
                    <x-table-table class="">
                        <x-table-thead>
                            <tr>
                                <x-table-th>Product</x-table-th>
                                <x-table-th>Requested Quantity</x-table-th>
                                <x-table-th>Received Quantity</x-table-th>
                                <x-table-th>Room</x-table-th>
                                <x-table-th>Rack</x-table-th>
                                <x-table-th>Location Quantity</x-table-th>
                            </tr>
                        </x-table-thead>
                        <x-table-tbody>
                            @foreach ($outboundRequest->requested_quantities as $productId => $quantity)
                                @if (isset($outboundRequestLocations[$productId]))
                                    @foreach ($outboundRequestLocations[$productId] as $key => $location)
                                        <x-table-tr>
                                            @if ($loop->first)
                                                <x-table-td rowspan="{{ count($outboundRequestLocations[$productId]) }}">
                                                    {{ optional($outboundRequest->sales->products->firstWhere('id', $productId))->name }}
                                                </x-table-td>
                                                <x-table-td rowspan="{{ count($outboundRequestLocations[$productId]) }}">
                                                    {{ $quantity }}
                                                </x-table-td>
                                                <x-table-td rowspan="{{ count($outboundRequestLocations[$productId]) }}">
                                                    {{ $outboundRequest->received_quantities[$productId] ?? 0 }}
                                                </x-table-td>
                                            @endif
                                            <x-table-td>{{ $location->room }}</x-table-td>
                                            <x-table-td>{{ $location->rack }}</x-table-td>
                                            <x-table-td>{{ $location->quantity }}</x-table-td>
                                        </x-table-tr>
                                    @endforeach
                                @else
                                    <x-table-tr>
                                        <x-table-td>{{ optional($outboundRequest->sales->products->firstWhere('id', $productId))->name }}
                                        </x-table-td>
                                        <x-table-td>{{ $quantity }}</x-table-td>
                                        <x-table-td colspan="3">No locations found for this product.</x-table-td>
                                    </x-table-tr>
                                @endif
                            @endforeach
                        </x-table-tbody>
                    </x-table-table>
                    </div>

                    <!-- Expedition Details -->
                    <div class="mb-3 mt-1 flex-grow border-t border-gray-300 dark:border-gray-700"></div>
                    <x-input-label>Expedition Details</x-input-label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
                        <div class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-300">Expedition</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ optional($outboundRequest->expedition)->name }}
                            </p>
                        </div>
                        <div class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-300">Tracking Number</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $outboundRequest->tracking_number }}
                            </p>
                        </div>
                        <div class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-300">Real Volume</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100"> {{ $outboundRequest->real_volume }} m³
                            </p>
                        </div>
                        <div class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-300">Real Weight</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100"> {{ $outboundRequest->real_weight }} kg
                            </p>
                        </div>
                        <div class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            <p class="text-sm text-gray-500 dark:text-gray-300">Real Shipping Fee</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100"> {{ $outboundRequest->real_shipping_fee }}
                            </p>
                        </div>                       
                    </div>
                    <!-- Notes -->
                    <div class="mb-3 mt-1 flex-grow border-t border-gray-300 dark:border-gray-700"></div>
                    <x-input-label>Notes</x-input-label>
                    <div class="mb-4 flex justify-between items-center">
                        <p class="p-3 bg-gray-50 border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                            {{ $outboundRequest->notes }}
                        </p>
                    
                        <!-- Actions -->
                        @if ($outboundRequest->status == 'Ready to Complete')
                            <a href="{{ route('outbound_requests.complete', $outboundRequest->id) }}" class="btn btn-primary"
                                onclick="return confirm('Are you sure you want to complete this request?')">
                                Complete Request
                            </a>
                            <x-button-add :route="route('outbound_requests.complete', $outboundRequest->id)" text="Complete Request" />
                        @endif
                    </div>
                    <div class="flex justify-end space-x-4">
                    
                    <x-button href="{{route('sales.index')}}"
                    class="border rounded border-gray-400 dark:border-gray-700 p-3 text-lg hover:underline text-gray-700 dark:text-gray-400">Cancel</x-button>
                    <x-button href="{{ route('outbound_requests.edit', $outboundRequest->id) }}" text="Edit Sales"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 dark:bg-green-700 dark:hover:bg-green-800">Edit Sales</x-button>
                    </div>
</x-company-layout>