<x-modal-create trigger="Tambah Kurir Baru" title="Tambah Kurir Baru">
    <form action="{{ route('couriers.store') }}" method="POST" class="">
        @csrf

        <!-- Kurir Code -->
        <div class="form-group mb-4">
            <x-input-label for="id">Kurir Code (Kode Toko)</x-input-label>
            <x-text-input type="text" id="code" name="code" class="w-full" required placeholder="Masukkan kode kurir" />
        </div>

        <!-- Kurir Name -->
        <div class="form-group mb-4">
            <x-input-label for="name">Kurir Name</x-input-label>
            <x-text-input type="text" id="name" name="name" class="w-full" required placeholder="Masukkan nama kurir" />
        </div>

        <!-- Contact Info -->
        <div class="form-group mb-4">
            <x-input-label for="contact_info">Contact Info (HP/Email)</x-input-label>
            <x-text-input type="text" id="contact_info" name="contact_info" class="w-full" required placeholder="Kontak untuk kurir ?"/>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4 mt-4">
            <x-primary-button type="submit">Create Kurir</x-primary-button>
            <button type="button" @click="isOpen = false" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700">Cancel</button>
        </div>
    </form>
</x-modal-create>
