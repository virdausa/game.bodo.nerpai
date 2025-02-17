<x-modal-create trigger="Create New Store" title="Create New Store">
    <form action="{{ route('stores.store') }}" method="POST" class="">
        @csrf

        <!-- Company Id -->
        <div class="form-group mb-4">
            <x-input-label for="id">Company Id (Kode Perusahaan)</x-input-label>
            <x-text-input type="text" id="id" name="id" class="w-full" required placeholder="Masukkan nama perusahaan" />
        </div>

        <!-- company Name -->
        <div class="form-group mb-4">
            <x-input-label for="name">Company Name</x-input-label>
            <x-text-input type="text" id="name" name="name" class="w-full" required placeholder="Masukkan nama perusahaan" />
        </div>

        <!-- Address -->
        <div class="form-group mb-4">
            <x-input-label for="address">Alamat</x-input-label>
            <x-text-input type="text" id="address" name="address" class="w-full" placeholder="Dimanakah lokasi perusahaan?"/>
        </div>

        <!-- Database -->
        <div class="form-group mb-4">
            <x-input-label for="database">Database Url</x-input-label>
            <x-text-input type="text" id="database" name="database" class="w-full" placeholder="Masukkan database url" />
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4 mt-4">
            <x-primary-button type="submit">Create company</x-primary-button>
            <button type="button" @click="isOpen = false" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700">Cancel</button>
        </div>
    </form>
</x-modal-create>
