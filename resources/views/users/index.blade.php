<x-lobby-layout>
    <div class="py-12">
        <div class="max-w-7xl my-10 mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h3 class="text-lg font-bold dark:text-white">Manage Users</h3>
                    <p class="text-sm dark:text-gray-200 mb-6">Create, edit, and manage your Users listings.</p>
                    <div class="my-6 flex-grow border-t border-gray-300 dark:border-gray-700"></div>

                    <!-- Search and Add New Supplier -->
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 mb-4">
                       
                        <div class="w-full md:w-auto flex justify-end">
                            <x-button-add :route="route('users.create')" text="Add User" />
                        </div>
                    </div>
                    <x-table-table id="search-table">
                        <x-table-thead >
                            <tr>
                                <x-table-th>ID</x-table-th>
                                {{-- <x-table-th>Username</x-table-th> --}}
                                <x-table-th>Nama</x-table-th>
                                <x-table-th>Role</x-table-th>
                                <x-table-th>Actions</x-table-th>
                            </tr>
                        </x-table-thead>
                        <x-table-tbody>
                            @foreach ($users as $User)
                                <tr>
                                    <x-table-td>{{ $User->id }}</x-table-td>
                                    {{-- <x-table-td>{{ $User->username }}</x-table-td> --}}
                                    <x-table-td>{{ $User->name }}</x-table-td>
                                    <x-table-td>{{ $User->role }}</x-table-td>
                                    <x-table-td class="flex justify-center items-center gap-2">
                                    <div class="flex items-center space-x-2">
                                            <x-button-edit :route="route('users.edit', $User->id)" />
                                            <form action="{{ route('users.destroy', $User->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <x-button-delete :route="route('users.destroy', $User->id)" />
                                            </form>
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
    
</x-lobby-layout>
