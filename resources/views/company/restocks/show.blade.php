@php
    $layout = session('layout');
@endphp

<x-dynamic-component :component="'layouts.' . $layout">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h1 class="text-2xl font-bold mb-6">Restock Details: {{ $store_restock->id }}</h1>
                    <div class="mb-3 mt-1 flex-grow border-t border-gray-500 dark:border-gray-700"></div>

                    <h3 class="text-lg font-bold my-3">Restock Details</h3>
                    <div class="grid grid-cols-3 sm:grid-cols-3 gap-6 mb-6">
                        <x-div-box-show title="Store">{{ $store_restock->store->name ?? 'N/A' }}</x-div-box-show>
                        <x-div-box-show title="Warehouse">{{ $store_restock->warehouse->name ?? 'N/A' }}</x-div-box-show>

                        <x-div-box-show title="Restock Date">{{ $store_restock->restock_date ?? 'N/A' }}</x-div-box-show>
                        <x-div-box-show title="Admin">{{ $store_restock->store_employee->employee->companyuser->user->name ?? 'N/A' }}</x-div-box-show>

                        <x-div-box-show title="Status">
                            <p
                                class="text-lg font-medium inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                {{ $store_restock->status ?? 'N/A' }}
                            </p>
                        </x-div-box-show>
                        <x-div-box-show title="Admin Notes">{{ $store_restock->admin_notes ?? 'N/A' }}</x-div-box-show>
                        <x-div-box-show title="Team Notes">{{ $store_restock->team_notes ?? 'N/A' }}</x-div-box-show>
                    </div>
                    <div class="mb-3 mt-1 flex-grow border-t border-gray-500 dark:border-gray-700"></div>


                    <h3 class="text-lg font-bold mt-6">Products</h3>
                    <x-table-table id="search-table">
                        <x-table-thead>
                            <tr>
                                <x-table-th>#</x-table-th>
                                <x-table-th>Product</x-table-th>
                                <x-table-th>Quantity</x-table-th>
                                <x-table-th>Cost</x-table-th>
                                <x-table-th>Notes</x-table-th>
                                <x-table-th>Actions</x-table-th>
                            </tr>
                        </x-table-thead>
                        <x-table-tbody>
                            @foreach ($store_restock->products as $index => $product)
                                <x-table-tr>
                                    <x-table-td>{{ $product->id }}</x-table-td>
                                    <x-table-td>{{ $product->id }} : {{ $product->name }}</x-table-td>
                                    <x-table-td>{{ $product->pivot->quantity }}</x-table-td>
                                    <x-table-td>{{ $product->pivot->cost_per_unit }}</x-table-td>
                                    <x-table-td>{{ $product->pivot->notes ?? 'N/A' }}</x-table-td>
                                    <x-table-td>
                                        <div class="flex items-center space-x-2">
                                        </div>
                                    </x-table-td>
                                </x-table-tr>
                            @endforeach
                        </x-table-tbody>
                    </x-table-table>


                    <!-- Action Section -->
                    <h3 class="text-lg font-bold my-3">Actions</h3>
                    @if ($store_restock->status == 'STR_REQUEST')
                    <div>
                        <div class="flex justify mt-4">
                            <form action="{{ route('store_restocks.action', ['store_restocks'=> $store_restock->id, 'action' => 'STR_PROCESS']) }}" method="POST">
                                @csrf
                                @method('POST')
                                <x-primary-button type="submit">Process Request Restock</x-primary-button>
                            </form>
                        </div>
                    </div>
                    @elseif ($store_restock->status == 'INB_PROCESS')
                    <div>
                        <div class="flex justify mt-4">
                            <form action="{{ route('store_restocks.action', ['store_restocks'=> $store_restock->id, 'action' => 'INB_COMPLETED']) }}" method="POST">
                                @csrf
                                @method('POST')
                                <x-primary-button type="submit">Process Restock Selesai :)</x-primary-button>
                            </form>
                        </div>
                    </div>
                    @endif

                    <div class="my-6 flex-grow border-t border-gray-500 dark:border-gray-700"></div>

                    <!-- Back Button -->
                    <x-secondary-button>
                        <a href="{{ route('restocks.index') }}">Back to List</a>
                    </x-secondary-button>
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
