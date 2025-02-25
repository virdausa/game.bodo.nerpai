<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-200">
            {{ __('Warehouse List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h1 class="text-2xl font-bold mb-6">Warehouse List</h1>
                    <div class="my-6 flex-grow border-t border-gray-300 dark:border-gray-700"></div>

                    <!-- Actions -->
                   
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 mb-4">
                        <div class="flex flex-col md:flex-row items-center space-x-3">
                            <!-- <a href="{{ route('warehouse_locations.index') }}" class="ml-2">
                                <x-secondary-button :route="route('warehouse_locations.index')">Manage Locations</x-secondary-button>
                            </a> -->
                            @include('warehouses.create')
                         </div>
                    </div>

                    <!-- Warehouse Table -->
                    <x-table-table id="search-table">
                        <x-table-thead>
                            <tr>
                                <x-table-th>ID</x-table-th>
                                <x-table-th>Name</x-table-th>
                                <x-table-th>Location</x-table-th>
                                <x-table-th>Actions</x-table-th>
                            </tr>
                        </x-table-thead>
                        <x-table-tbody>
                            @foreach ($warehouses as $warehouse)
                                <x-table-tr>
                                    <x-table-td>{{ $warehouse->id }}</x-table-td>
                                    <x-table-td>{{ $warehouse->name }}</x-table-td>
                                    <x-table-td>{{ $warehouse->address }}</x-table-td>
                                    <x-table-td>
                                        <div class="flex items-center space-x-2">
                                            <x-button-show :route="route('warehouses.show', $warehouse->id)" />
                                            @include('warehouses.edit', ['id' => $warehouse->id])
                                            <x-button-delete :route="route('warehouses.destroy', $warehouse->id)" />
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
    
</x-app-layout>
