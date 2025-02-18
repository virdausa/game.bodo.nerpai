<x-modal-create trigger="Create New Company" title="Create New Company">
    <form action="{{ route('companies.store') }}" method="POST" class="">
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
            <x-primary-button id="submit-button" type="submit">Create company</x-primary-button>
            <button type="button" @click="isOpen = false" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700">Cancel</button>
        </div>
    </form>

    <div id="loading-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="text-center">
            <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <p class="mt-2 text-white text-xl">Proses setup perusahaan... Mohon tunggu...</p>
        </div>
    </div>

<script>
    $('#submit-button').on('click', function(e) {
        e.preventDefault(); // Mencegah submit default

        // Validasi form
        let form = $(this).closest('form')[0];
        if (form.checkValidity()) {
            // Disable tombol submit dan tampilkan overlay spinner jika form valid
            $(this).prop('disabled', true);
            $('#loading-overlay').removeClass('hidden');
            
            // Submit form setelah spinner muncul
            form.submit();
        } else {
            // Tampilkan pesan error atau beri tahu user bahwa form tidak valid
            alert('Please fill in all required fields');
        }
    });
</script>

</x-modal-create>
