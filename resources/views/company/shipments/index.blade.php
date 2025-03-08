@php
    $layout = session('layout');
@endphp
<x-dynamic-component :component="'layouts.' . $layout">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shipments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h3 class="text-2xl font-bold dark:text-white">Manage Shipments</h3>
                    <p class="text-sm dark:text-gray-200 mb-6">Create, edit, and manage your shipment listings.</p>
                    <div class="my-6 flex-grow border-t border-gray-300 dark:border-gray-700"></div>

                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 mb-4">
                        
                        <div class="w-full md:w-auto flex justify-end">
                            <a href="{{ route('shipments.create') }}">
                                <x-button-add :route="route('shipments.create')" text="Create New Shipment" />
                            </a>
                        </div>
                    </div>

                    <x-table-table id="search-table">
                        <x-table-thead>
                            <tr>
                                <x-table-th>ID</x-table-th>
                                <x-table-th>Pengirim</x-table-th>
                                <x-table-th>Penerima</x-table-th>
                                <x-table-th>Transaksi</x-table-th>
                                <x-table-th>Tanggal</x-table-th>
                                <x-table-th>Status</x-table-th>
                                <x-table-th>Notes</x-table-th>
                                <x-table-th>Actions</x-table-th>
                            </tr>
                        </x-table-thead>
                        <x-table-tbody>
                            @foreach ($shipments as $shipment)
                                <x-table-tr>
                                    <x-table-td>{{ $shipment->id }}</x-table-td>
                                    <x-table-td>{{ $shipment->shipper_type }} : {{ $shipment->shipper?->name }}</x-table-td>
                                    <x-table-td>{{ $shipment->consignee_type }} : {{ $shipment->consignee?->name }}</x-table-td>
                                    <x-table-td>{{ $shipment->transaction_type }} : {{ $shipment->transaction?->number }}</x-table-td>
                                    <x-table-td>{{ $shipment->ship_date }}</x-table-td>
                                    <x-table-td>{{ $shipment->status }}</x-table-td>
                                    <x-table-td>{{ $shipment->notes }}</x-table-td>
                                    <x-table-td>
                                        <div class="flex items-center space-x-2">
                                            <x-button-show :route="route('shipments.show', $shipment->id)" />
                                            <!-- <x-button-edit :route="route('shipments.edit', $shipment->id)" />
                                            <x-button-delete :route="route('shipments.destroy', $shipment->id)" /> -->
                                        </div>
                                    </x-table-td>
                                </x-table-tr>
                            @endforeach
                        </x-table-tbody>
                    </x-table-table>
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
