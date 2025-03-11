<x-company-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h1 class="text-2xl font-bold mb-6">Sales Details : {{ $sale->number }}</h1>
                    <div class="mb-3 mt-1 flex-grow border-t border-gray-300 dark:border-gray-700"></div>

                    <h3 class="text-lg font-bold my-3">General Informations</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
                        <x-div-box-show title="SO Number">{{ $sale->number }}</x-div-box-show>
                        <x-div-box-show title="Sale Date">{{ $sale->date }}</x-div-box-show>
                        <x-div-box-show title="Status">{{ ucfirst($sale->status) }}</x-div-box-show>
                        <x-div-box-show title="Consignee">{{ $sale?->consignee_type ?? 'N/A' }} : {{ $sale->consignee?->name ?? 'N/A' }}</x-div-box-show>
                        <x-div-box-show title="Warehouse">{{ $sale->warehouse->name }}</x-div-box-show>
                        <x-div-box-show title="Admin">{{ $sale->employee->companyuser->user->name ?? 'N/A' }}</x-div-box-show>
                        <x-div-box-show title="Customer Notes">{{ $sale->customer_notes ?? 'N/A' }}</x-div-box-show>
                        <x-div-box-show title="Admin Notes">{{ $sale->admin_notes ?? 'N/A' }}</x-div-box-show>
                    </div>
                    <!-- <div class="mb-3 mt-1 flex-grow border-t border-gray-300 dark:border-gray-700"></div> -->
                    
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
                    <!-- <div class="mb-3 mt-1 flex-grow border-t border-gray-300 dark:border-gray-700"></div> -->
                    

                    <h3 class="text-lg font-bold my-3">Shipment Details</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
                        <x-div-box-show title="Courier">{{ $sale->courier?->name ?? 'N/A' }}</x-div-box-show>
                        <x-div-box-show title="Estimated Shipping Fee">${{ number_format($sale->estimated_shipping_fee, 2) }}</x-div-box-show>
                        <x-div-box-show title="Packing Fee">${{ number_format($sale->packing_fee, 2) }}</x-div-box-show>
                    </div>
                    <div class="mb-3 mt-1 flex-grow border-t border-gray-300 dark:border-gray-700"></div>
                    
                    <!-- Invoice Section -->
                    <h3 class="text-lg font-bold my-3">Invoices</h3>
                    <div class="overflow-x-auto">
                        <x-table-table>
                            <x-table-thead>
                                <tr class>
                                    <x-table-th>Id</x-table-th>
                                    <x-table-th>Invoice Number</x-table-th>
                                    <x-table-th>Date</x-table-th>
                                    <x-table-th>Due Date</x-table-th>
                                    <x-table-th>Total Amount</x-table-th>
                                    <x-table-th>Notes</x-table-th>
                                    <x-table-th>Status</x-table-th>
                                    <x-table-th>Actions</x-table-th>
                                </tr>
                            </x-table-thead>
                            <x-table-tbody>
                                @foreach ($sale->invoices as $invoice)
                                    <x-table-tr>
                                        <x-table-td>{{ $invoice->id }}</x-table-td>
                                        <x-table-td>{{ $invoice->number }}</x-table-td>
                                        <x-table-td>{{ $invoice->date?->format('Y-m-d') ?? 'N/A' }}</x-table-td>
                                        <x-table-td>{{ $invoice->due_date?->format('Y-m-d') ?? 'N/A' }}</x-table-td>
                                        <x-table-td>Rp{{ number_format($invoice->total_amount, 2) }}</x-table-td>
                                        <x-table-td>{{ $invoice->notes }}</x-table-td>
                                        <x-table-td>{{ $invoice->status }}</x-table-td>
                                        <x-table-td>
                                            <div class="flex space-x-2">
                                                <x-button-show :route="route('sale_invoices.show', $invoice->id)" />
                                                <!-- <x-button-edit :route="route('sale_invoices.edit', $invoice->id)" /> -->
                                                <!-- <x-button-delete :route="route('sale_invoices.destroy', $invoice->id)" /> -->
                                            </div>
                                        </x-table-td>
                                    </x-table-tr>
                                @endforeach
                            </x-table-tbody>
                        </x-table-table>
                    </div>
                    <div class="my-6 flex-grow border-t border-gray-500 dark:border-gray-700"></div>

                    <!-- Outbound Section -->
                    <h3 class="text-lg font-bold mt-6">Outbounds</h3>
                    <x-table-table id="search-table">
                        <x-table-thead>
                            <tr>
                                <x-table-th>ID</x-table-th>
                                <x-table-th>Number</x-table-th>
                                <x-table-th>Date</x-table-th>
                                <x-table-th>Admin</x-table-th>
                                <x-table-th>Status</x-table-th>
                                <x-table-th>Notes</x-table-th>
                                <x-table-th>Actions</x-table-th>
                            </tr>
                        </x-table-thead>
                        <x-table-tbody>
                            @foreach($sale->outbounds as $outbound)
                                <x-table-tr>
                                    <x-table-td>{{ $outbound->id }}</x-table-td>
                                    <x-table-td>{{ $outbound->number }}</x-table-td>
                                    <x-table-td>{{ $outbound->date?->format('Y-m-d') ?? 'N/A' }}</x-table-td>
                                    <x-table-td>{{ $outbound->employee?->companyuser->user->name ?? 'N/A' }}</x-table-td>
                                    <x-table-td>{{ $outbound->status }}</x-table-td>
                                    <x-table-td>{{ $outbound->notes }}</x-table-td>
                                    <x-table-td>
                                        <x-button-show :route="route('warehouse_outbounds.show', $outbound->id)" />
                                    </x-table-td>
                                </x-table-tr>
                            @endforeach
                        </x-table-tbody>
                    </x-table-table>
                    <div class="my-6 flex-grow border-t border-gray-500 dark:border-gray-700"></div>

                    <!-- Action Section -->
                    <h3 class="text-lg font-bold my-3">Actions</h3>
                    <div>
                        @if ($sale->status == 'SO_OFFER')
                        <div class="flex justify mt-4">
                            <form action="{{ route('sales.action', ['id' => $sale->id, 'action' => 'SO_REQUEST']) }}" method="POST">
                                @csrf
                                @method('POST')
                                <x-primary-button type="submit">Kirim Penjualan ke Customer</x-primary-button>
                            </form>
                        </div>
                        @elseif ($sale->status == 'SO_REQUEST')
                        <div class="flex justify-end m-4">
                        <form action="{{ route('sales.action', ['id' => $sale->id, 'action' => 'SO_CONFIRMED']) }}" method="POST">
                                @csrf
                                @method('POST')
                                <x-primary-button type="submit">Input Invoice untuk Customer</x-primary-button>
                            </form>
                        </div>
                        @endif

                        @if ($sale->status == 'SO_CONFIRMED')
                        <div class="flex justify mt-4">
                            <form action="{{ route('sales.action', ['id' => $sale->id, 'action' => 'SO_DP_CONFIRMED']) }}" method="POST">
                                @csrf
                                @method('POST')
                                <x-primary-button type="submit">Konfirmasi Pembayaran/DP Lunas dari Customer</x-primary-button>
                            </form>
                        </div>
                        @endif

                        @if ($sale->status == 'SO_DP_CONFIRMED')
                        <div class="flex justify mt-4">
                            <form action="{{ route('sales.action', ['id' => $sale->id, 'action' => 'SO_OUTBOUND_REQUEST']) }}" method="POST">
                                @csrf
                                @method('POST')
                                <x-primary-button type="submit">Request Outbound Gudang</x-primary-button>
                            </form>
                        </div>
                        @endif

                        @if ($sale->status == 'SO_OUTBOUND_REQUEST')
                        <div class="flex justify mt-4">
                            <form action="{{ route('sales.action', ['id' => $sale->id, 'action' => 'SO_PAYMENT_COMPLETION']) }}" method="POST">
                                @csrf
                                @method('POST')
                                <x-primary-button type="submit">Koordinasi Pembayaran Pelunasan</x-primary-button>
                            </form>
                        </div>
                        @endif

                        @if ($sale->status == 'SO_PAYMENT_COMPLETION')
                        <div class="flex justify mt-4">
                            <form action="{{ route('sales.action', ['id' => $sale->id, 'action' => 'SO_COMPLETED']) }}" method="POST">
                                @csrf
                                @method('POST')
                                <x-primary-button type="submit">Completed Sales Order</x-primary-button>
                            </form>
                        </div>
                        @endif
                    </div>

                    <div class="my-6 flex-grow border-t border-gray-500 dark:border-gray-700"></div>

                    <div class="flex justify-end space-x-4">
                        <x-secondary-button>
                            <a href="{{ route('sales.index') }}">Back to List</a>
                        </x-secondary-button>
                        @if($sale->status == 'SO_OFFER' || $sale->status == 'SO_REQUEST')
                        <x-button href="{{route('sales.edit', $sale->id)}}" text="Edit Sales"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 dark:bg-green-700 dark:hover:bg-green-800">Edit Sales</x-button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</x-company-layout>