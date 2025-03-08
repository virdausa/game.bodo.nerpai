<x-company-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-200">
            {{ __('Create New Supplier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h1 class="text-2xl font-bold mb-6">Create New Supplier</h1>

                    <form action="{{ route('suppliers.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Supplier Name -->
                        <div class="form-group">
                            <x-input-label for="name">Supplier Name</x-input-label>
                            <x-text-input type="text" id="name" name="name" class="w-full" required />
                        </div>

                        <!-- Address -->
                        <div class="form-group">
                            <x-input-label for="address">Address</x-input-label>
                            <x-text-input type="text" id="address" name="address" class="w-full" />
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <x-input-label for="email">Email</x-input-label>
                            <x-text-input type="text" id="email" name="email" class="w-full" />
                        </div>

                        <!-- Phone Number -->
                        <div class="form-group">
                            <x-input-label for="phone_number">Phone Number</x-input-label>
                            <x-text-input type="text" id="phone_number" name="phone_number" class="w-full" />
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <x-input-label for="status">Status</x-input-label>
                            <select id="status" name="status"
                                class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="form-group">
                            <x-input-label for="notes">Notes</x-input-label>
                            <textarea id="notes" name="notes"
                                class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white dark:border-gray-600"></textarea>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-4">
                            <x-primary-button type="submit">Create Supplier</x-primary-button>
                            <x-button href="{{ route('suppliers.index') }}">Cancel</x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-company-layout>
