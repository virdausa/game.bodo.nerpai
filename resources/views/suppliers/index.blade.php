<x-app-layout>
    <div class="pt-12">
        <div class="max-w-7xl my-10 mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h3 class="text-lg font-bold dark:text-white">Manage Suppliers</h3>
                    <p class="text-sm dark:text-gray-200 mb-6">Create, edit, and manage your supplier listings.</p>
                    <div class="my-6 flex-grow border-t border-gray-300 dark:border-gray-700"></div>

                    <!-- Search and Add New Supplier -->
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 mb-4">
                       
                        <div class="w-full md:w-auto flex justify-end">
                            <x-button-add :route="route('suppliers.create')" text="Add Supplier" />
                        </div>
                    </div>

                    <!-- Supplier Table -->
                    <x-table-table id="search-table">
                        <x-table-thead>
                            <tr>
                                <x-table-th>ID</x-table-th>
                                <x-table-th>Name</x-table-th>
                                <x-table-th>Address</x-table-th>
                                <x-table-th>Email</x-table-th>
                                <x-table-th>Phone Number</x-table-th>
                                <x-table-th>Status</x-table-th>
                                <x-table-th>Note</x-table-th>
                                <x-table-th>Actions</x-table-th>
                            </tr>
                        </x-table-thead>
                        <x-table-tbody>
                            @foreach ($suppliers as $supplier)
                                <x-table-tr>
                                    <x-table-td>{{ $supplier->id }}</x-table-td>
                                    <x-table-td>{{ $supplier->name }}</x-table-td>
                                    <x-table-td>{{ $supplier->address }}</x-table-td>
                                    <x-table-td>{{ $supplier->email }}</x-table-td>
                                    <x-table-td>{{ $supplier->phone_number }}</x-table-td>
                                    <x-table-td>{{ $supplier->status }}</x-table-td>
                                    <x-table-td>{{ $supplier->notes }}</x-table-td>
                                    <x-table-td>
                                    <div class="flex items-center space-x-2">
                                            
                                            <x-button-edit :route="route('suppliers.edit', $supplier->id)" />
                                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <x-button-delete :route="route('suppliers.destroy', $supplier->id)" />
                                            </form>
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
