<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Couriers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h3 class="text-lg font-bold dark:text-white">Manage Couriers</h3>
                    <p class="text-sm dark:text-gray-200 mb-6">Create, edit, and manage your courier listings.</p>
                    <div class="my-6 flex-grow border-t border-gray-300 dark:border-gray-700"></div>

                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 mb-4">
                        
                        <div class="w-full md:w-auto flex justify-end">
                            <a href="{{ route('couriers.create') }}">
                                <x-button-add :route="route('couriers.create')" text="Tambah Kurir" />
                            </a>
                        </div>
                    </div>

                    <x-table-table id="search-table">
                        <x-table-thead>
                            <tr>
                                <x-table-th>ID</x-table-th>
                                <x-table-th>Kode</x-table-th>
                                <x-table-th>Nama</x-table-th>
                                <x-table-th>Kontak Info</x-table-th>
                                <x-table-th>Website</x-table-th>
                                <x-table-th>Status</x-table-th>
                                <x-table-th>Notes</x-table-th>
                                <x-table-th>Actions</x-table-th>
                            </tr>
                        </x-table-thead>
                        <x-table-tbody>
                            @foreach ($couriers as $courier)
                                <x-table-tr>
                                    <x-table-td>{{ $courier->id }}</x-table-td>
                                    <x-table-td>{{ $courier->code }}</x-table-td>
                                    <x-table-td>{{ $courier->name }}</x-table-td>
                                    <x-table-td>{{ $courier->contact_info }}</x-table-td>
                                    <x-table-td>{{ $courier->website }}</x-table-td>
                                    <x-table-td>{{ $courier->status }}</x-table-td>
                                    <x-table-td>{{ $courier->notes }}</x-table-td>
                                    <x-table-td>
                                        <div class="flex items-center space-x-2">
                                            <x-button-show :route="route('couriers.show', $courier->id)" />
                                            <x-button-edit :route="route('couriers.edit', $courier->id)" />
                                            <form action="{{ route('couriers.destroy', $courier->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <x-button-delete :route="route('couriers.destroy', $courier->id)" />
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
