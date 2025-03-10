@php
    $layout = session('layout');
@endphp
<x-dynamic-component :component="'layouts.' . $layout">
    <div class="py-12">
        <div class="max-w-7xl my-10 mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h3 class="text-lg font-bold dark:text-white">Manage Accounts Payables</h3>
                    <p class="text-sm dark:text-gray-200 mb-6">Create, edit, and manage your Payables listings.</p>
                    <div class="my-6 flex-grow border-t border-gray-300 dark:border-gray-700"></div>
                     
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 mb-4">             
                        <div class="flex flex-col md:flex-row items-center space-x-3">

                         </div>
                    </div>

                    <!-- Supplier Table -->
                    <x-table-table id="search-table">
                        <x-table-thead>
                            <tr>
                                <x-table-th>ID</x-table-th>
                                <x-table-th>Name</x-table-th>
                                <x-table-th>Status</x-table-th>
                                <x-table-th>Hutang</x-table-th>
                                <x-table-th>Note</x-table-th>
                                <x-table-th>Actions</x-table-th>
                            </tr>
                        </x-table-thead>
                        <x-table-tbody>
                            @foreach ($suppliers as $supplier)
                                @php
                                    $unpaid_amount = $supplier->payables->sum('total_amount');
                                    $paid_amount = $supplier->payables->sum('balance');
                                @endphp

                                @if($unpaid_amount != $paid_amount) 
                                    <x-table-tr>
                                        <x-table-td>{{ $supplier->id }}</x-table-td>
                                        <x-table-td>{{ $supplier->name }}</x-table-td>
                                        <x-table-td>{{ $supplier->status }}</x-table-td>
                                        <x-table-td>Rp.{{ (number_format($unpaid_amount - $paid_amount, 2)) }}</x-table-td>
                                        <x-table-td>{{ $supplier->notes }}</x-table-td>
                                        <x-table-td>
                                            <div class="flex items-center space-x-2">
                                                <x-button-show :route="route('payables.show', $supplier->id)" />
                                            </div>
                                        </x-table-td>
                                    </x-table-tr>
                                @endif
                            @endforeach
                        </x-table-tbody>
                    </x-table-table>
                </div>
            </div>
        </div>
    </div>
    
</x-dynamic-component>
