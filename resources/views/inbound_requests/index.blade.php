<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h3 class="text-lg font-bold dark:text-white mb-4">Manage Inbound Requests</h3>
                    <p class="text-sm dark:text-gray-200 mb-6">Manage your inbound requests orders.</p>
                    <div class="my-6 flex-grow border-t border-gray-300 dark:border-gray-700"></div>
                    <x-table-table id="search-table">
                        <x-table-thead>
                            <tr>
                                <x-table-th>Purchase Order</x-table-th>
                                <x-table-th>Warehouse</x-table-th>
                                <x-table-th>Status</x-table-th>
                                <x-table-th>Requested Quantities</x-table-th>
                                <x-table-th>Verified By</x-table-th>
                                <x-table-th>Notes</x-table-th>
                                <x-table-th>Actions</x-table-th>
                            </tr>
                        </x-table-thead>
                        <x-table-tbody>
                            @foreach($inboundRequests as $request)
                                <x-table-tr>
                                    <x-table-td>{{ $request->purchase->id }}</x-table-td>
                                    <x-table-td>{{ $request->warehouse->name }}</x-table-td>
                                    <x-table-td>{{ $request->status }}</x-table-td>
                                    <x-table-td>
                                        @foreach($request->received_quantities as $productId => $qty)
                                            {{ $productId }}: {{ $request->requested_quantities[$productId] }} / {{ $qty }}<br>
                                        @endforeach
                                    </x-table-td>
                                    <x-table-td>{{ optional($request->verifier)->name }}</x-table-td>
                                    <x-table-td>{{ $request->notes }}</x-table-td>
                                    <x-table-td>
                                        <x-button-show :route="route('inbound_requests.show', $request->id)" />

                                        @if ($request->status !== 'Completed')
                                            <x-button-edit :route="route('inbound_requests.edit', $request->id)" />


                                        @endif
                                        </x-table-td>
                                        </x-table-tr>
                            @endforeach
                                    </x-table-tbody>
                                    </x-table-table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>