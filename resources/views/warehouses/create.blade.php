<x-modal-create trigger="Create New Warehouse" title="Create New Warehouse">
    <form action="{{ route('warehouses.store') }}" method="POST" class="">
        @csrf

        <!-- Warehouse Name -->
        <div class="form-group mb-4">
            <x-input-label for="name">Warehouse Name</x-input-label>
            <x-text-input type="text" id="name" name="name" class="w-full" required placeholder="Masukkan nama gudang" />
        </div>

        <!-- Address -->
        <div class="form-group mb-4">
            <x-input-label for="address">Address</x-input-label>
            <x-text-input type="text" id="address" name="address" class="w-full" placeholder="Dimanakah lokasi gudangnya?"/>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
            <x-primary-button type="submit">Save Warehouse</x-primary-button>
            <button type="button" @click="isOpen = false" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700">Cancel</button>
        </div>
    </form>
</x-modal-create>
