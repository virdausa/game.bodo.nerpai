<x-company-layout>
    <div class="py-12">
        <div class="max-w-7xl my-10 mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h3 class="text-lg font-bold dark:text-white">Manage Customers</h3>
                    <p class="text-sm dark:text-gray-200 mb-6">Create, edit, and manage your customers listings.</p>
                    <div class="my-6 flex-grow border-t border-gray-300 dark:border-gray-700"></div>

                    <!-- Search and Add New Customer -->
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 mb-4">
                       
                        <div class="w-full md:w-auto flex justify-end">
                            <x-button-add :route="route('customers.create')" text="Tambah Customer" />
                        </div>
                    </div>
                    <x-table-table id="search-table">
                        <x-table-thead >
                            <tr>
                                <x-table-th>ID</x-table-th>
                                <x-table-th>Name</x-table-th>
                                <x-table-th>Email</x-table-th>
                                <x-table-th>Phone Number</x-table-th>
                                <x-table-th>Address</x-table-th>
                                <x-table-th>Status</x-table-th>
                                <x-table-th>Reg Date</x-table-th>
                                <x-table-th>Actions</x-table-th>
                            </tr>
                        </x-table-thead>
                        <x-table-tbody>
                            @foreach ($customers as $customer)
                                <tr>
                                    <x-table-td>{{ $customer->id }}</x-table-td>
                                    <x-table-td>{{ $customer->name }}</x-table-td>
                                    <x-table-td>{{ $customer->email }}</x-table-td>
                                    <x-table-td>{{ $customer->phone_number }}</x-table-td>
                                    <x-table-td>{{ $customer->address }}</x-table-td>
                                    <x-table-td>{{ $customer->status }}</x-table-td>
                                    <x-table-td>{{ $customer->created_at }}</x-table-td>
                                    <x-table-td class="flex justify-center items-center gap-2">
                                        <div class="flex items-center space-x-2">
                                            <x-button-show :route="route('customers.show', $customer->id)" />
                                            <x-button-edit :route="route('customers.edit', $customer->id)" />
                                            <x-button-delete :route="route('customers.destroy', $customer->id)" />
                                        </div>
                                    </x-table-td>
                                </tr>
                            @endforeach
                        </x-table-tbody>
                    </x-table-table>
                </div>
            </div>
        </div>
    </div>
    
</x-company-layout>
