<x-modal-create trigger="Request Restock" title="Request Restock">
    <form action="{{ route('inventory_transfers.storeRequest') }}" method="POST" class="">
        @csrf
        
        <div class="form-group mb-4">
            <x-input-label for="team_notes" class="block text-sm font-medium text-gray-700">Team Notes</x-input-label>
            <x-input-textarea name="team_notes" id="team_notes" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" placeholder="Opsional, catatan untuk admin"></x-textarea>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4 mt-4">
            <x-primary-button type="submit">Request Restock</x-primary-button>
            <button type="button" @click="isOpen = false" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700">Cancel</button>
        </div>
    </form>
</x-modal-create>
