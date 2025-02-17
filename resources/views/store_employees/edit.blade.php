<x-modal-edit trigger="Edit" title="Edit Store Employee {$employee->employee->companyuser->user->name}">
    <form action="{{ route('store_employees.update', $employee->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Store Role -->
        <div class="form-group">
            <x-input-label for="Store Role">Store Role</x-input-label>
            <x-text-input type="text" id="Store Role" name="Store Role" class="w-full" value="{{ $employee->role }}" />
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
            <x-primary-button type="submit">Update Employee</x-primary-button>
            <button type="button" @click="isOpen = false"
                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700">Cancel</button>
        </div>
    </form>
</x-modal-edit>