<x-modal-create trigger="Create New company" title="Create New company">
    <form action="{{ route('companies.store') }}" method="POST" class="">
        @csrf

        <!-- company Name -->
        <div class="form-group">
            <x-input-label for="name">company Name</x-input-label>
            <x-text-input type="text" id="name" name="name" class="w-full" required placeholder="Masukkan nama gudang" />
        </div>

        <!-- Location -->
        <div class="form-group">
            <x-input-label for="location">Location</x-input-label>
            <x-text-input type="text" id="location" name="location" class="w-full" placeholder="Dimanakah lokasi gudangnya?"/>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
            <x-primary-button type="submit">Save company</x-primary-button>
            <button type="button" @click="isOpen = false" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700">Cancel</button>
        </div>
    </form>
</x-modal-create>
