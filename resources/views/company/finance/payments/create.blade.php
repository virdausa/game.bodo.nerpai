<x-modal-create trigger="Create Inventory Transfer" title="Create Inventory Transfer">
    <form action="{{ route('inventory_transfers.store') }}" method="POST" class="">
        @csrf
        
        <!-- Select Consignee Type -->
        <div class="form-group mb-4">
            <x-input-label for="consignee_type" class="block text-sm font-medium text-gray-700">Select Consignee Type</x-input-label>
            <x-input-select name="consignee_type" id="consignee_type" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
                <option value="ST">Store</option>
                <option value="WH">Warehouse</option>
            </x-input-select>
        </div>

        <!-- Select Consignee -->
        <div class="form-group mb-4">
            <x-input-label for="consignee_id" class="block text-sm font-medium text-gray-700">Select Consignee</x-input-label>
            <x-input-select name="consignee_id" id="consignee_id" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
                <!-- diisi pakai javascript -->
            </x-input-select>
        </div>

        <div class="form-group mb-4">
            <x-input-label for="admin_notes" class="block text-sm font-medium text-gray-700">Admin Notes</x-input-label>
            <x-input-textarea name="admin_notes" id="admin_notes" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" placeholder="Opsional, catatan untuk team"></x-textarea>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4 mt-4">
            <x-primary-button type="submit">Create Inventory Transfer</x-primary-button>
            <button type="button" @click="isOpen = false" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700">Cancel</button>
        </div>
    </form>
</x-modal-create>
