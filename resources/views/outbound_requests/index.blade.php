<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h3 class="text-lg font-bold dark:text-white">Manage Outbound Requests</h3>
                    <p class="text-sm dark:text-gray-200 mb-6">Create, edit, and manage your outbound requests.</p>
                    <div class="my-6 flex-grow border-t border-gray-300 dark:border-gray-700"></div>
                    <x-table-table id="search-table">
                        <x-table-thead>
                            <tr>
                                <x-table-th>Sales Order</x-table-th>
                                <x-table-th>Warehouse</x-table-th>
                                <x-table-th>Status</x-table-th>
                                <x-table-th>Received Quantities</x-table-th>
                                <x-table-th>Notes</x-table-th>
                                <x-table-th>Actions</x-table-th>
                            </tr>
                        </x-table-thead>
                        <x-table-tbody>
                            @foreach($outboundRequests as $request)
                                <x-table-tr>
                                    <x-table-td>{{ $request->sales->id }}</x-table-td>
                                    <x-table-td>{{ $request->warehouse->name }}</x-table-td>
                                    <x-table-td>{{ $request->status }}</x-table-td>
                                    <x-table-td>
                                        @foreach ($request->received_quantities as $productId => $quantity)
                                            {{ $request->sales->products->find($productId)->name }}:
                                            {{ $request->requested_quantities[$productId] }} / {{ $quantity }}<br>
                                        @endforeach
                                    </x-table-td>
                                    <x-table-td>{{ $request->notes }}</x-table-td>
                                    <x-table-td>
                                        <div class="flex justify-center gap-2">
                                    <x-button-show :route="route('outbound_requests.show', $request->id)" />

                                        @if ($request->status != 'Ready to Complete' && $request->status != 'Completed')
                                        <x-button-edit :route="route('outbound_requests.edit', $request->id)" />

                                        @endif
                                        </div>
                                    </x-table-td>
                                </x-table-tr>
                            @endforeach
                        </x-table-tbody>
                    </x-table-table>
</x-app-layout>