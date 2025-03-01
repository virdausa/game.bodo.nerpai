<x-store-layout>
    <div class="py-12">
        <div class="max-w-7xl my-10 mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h3 class="text-lg font-bold dark:text-white">Manage Restock</h3>
                    <p class="text-sm dark:text-gray-200 mb-6">Create, edit, and manage your Restocks listings.</p>
                    <div class="my-6 flex-grow border-t border-gray-300 dark:border-gray-700"></div>

                    <!-- Search and Add New Supplier -->
                     
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 mb-4">             
                        <div class="flex flex-col md:flex-row items-center space-x-3">
                                @include('store.store_restocks.create')
                         </div>
                    </div>
                    <x-table-table id="search-table">
                        <x-table-thead >
                            <tr>
                                <x-table-th>ID</x-table-th>
                                <x-table-th>Number</x-table-th>
                                <x-table-th>Date</x-table-th>
                                <x-table-th>Amount</x-table-th>
                                <x-table-th>Team</x-table-th>
                                <x-table-th>Status</x-table-th>
                                <x-table-th>Actions</x-table-th>
                            </tr>
                        </x-table-thead>
                        <x-table-tbody>
                            @foreach ($store_restocks as $restock)
                                <tr>
                                    <x-table-td>{{ $restock->id }}</x-table-td>
                                    <x-table-td>{{ $restock->number }}</x-table-td>
                                    <x-table-td>{{ $restock->restock_date }}</x-table-td>
                                    <x-table-td>{{ $restock->total_amount }}</x-table-td>
                                    <x-table-td>{{ $restock->store_employee->employee->companyuser->user->name }}</x-table-td>
                                    <x-table-td>{{ $restock->status }}</x-table-td>
                                    <x-table-td class="flex justify-center items-center gap-2">
                                    <div class="flex items-center space-x-2">
                                            @if($restock->status == 'STR_REQUEST') 
                                                <form action="{{ route('store_restocks.cancel', $restock->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-secondary-button type="submit">Cancel Request</x-secondary-button>
                                                </form> 
                                            @endif
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
    
</x-store-layout>
