<x-app-layout>
    <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-white">
        <h3 class="text-lg font-bold dark:text-white">Edit Kurir</h3>
        <div class="my-6 flex-grow border-t border-gray-300 dark:border-gray-700"></div>

        <form action="{{ route('couriers.update', $courier->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <x-input-label for="code" :value="__('Kode Kurir')" />
                <x-input-input type="text" name="code" x-model="code"
                    value="{{ $courier->code }}" required />
            </div>

            <div class="mb-4">
                <x-input-label for="name" :value="__('Nama Kurir')" />
                <x-input-input type="text" name="name" x-model="name"
                    value="{{ $courier->name }}" required />
            </div>

            <div class="mb-4">
                <x-input-label for="contact_info" :value="__('Contact Info')" />
                <x-input-input type="text" name="contact_info" x-model="contact_info"
                    value="{{ $courier->contact_info }}" required />
            </div>

            <div class="mb-4">
                <x-input-label for="website" :value="__('Link Website')" />
                <x-input-input type="text" name="website" x-model="website"
                    value="{{ $courier->website }}" required />
            </div>

            <div class="mb-4">
                <x-input-label for="status" :value="__('Status')" />
                <x-input-select name="status" x-model="status" required>
                    <x-select-option value="active" :selected="$courier->status === 'active'">Active</x-select-option>
                    <x-select-option value="inactive" :selected="$courier->status === 'inactive'">Inactive</x-select-option>
                </x-input-select>
            </div>
            <div class="mb-4">
                <x-input-label for="notes" :value="__('Notes')" />
                <x-input-textarea name="notes"
                    x-model="notes">{{ $courier->notes }}</x-input-textarea>
            </div>

            <div class="mb-3 mt-1 flex-grow border-t border-gray-300 dark:border-gray-700"></div>
            <div class="flex justify-end space-x-4">
                <a href="{{ route('couriers.index') }}">
                    <x-secondary-button type="button">Cancel</x-secondary-button>
                </a>
                <x-primary-button type="submit">Update Kurir</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
