<x-company-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h1 class="text-2xl font-bold dark:text-white">Edit Shipment: {{ $shipment->shipment_number }}</h1>
                    <p class="text-sm dark:text-gray-200 mb-3">Update the details of your shipment.</p>

                    <div class="p-2 border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-600 mb-4">
                        <form action="{{ route('shipments.update', $shipment->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                                <div class="form-group">
                                    <x-input-label for="ship_date">Shipment Date</x-input-label>
                                    <input type="date" name="ship_date" class="bg-gray-100 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" value="{{ ($shipment->ship_date)->format('Y-m-d') }}">
                                </div>

                                <div class="form-group">
                                    <x-input-label for="courier_id">Expedition Kurir</x-input-label>
                                    <select name="courier_id" class="bg-gray-100 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" required>
                                        @foreach($couriers as $courier)
                                            <option value="{{ $courier->id }}" {{ $shipment->courier_id == $courier->id ? 'selected' : '' }}>
                                                {{ $courier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-4">
                                    <x-input-label for="tracking_number">Tracking Number</x-input-label>
                                    <x-input-input type="text" name="tracking_number" value="{{ $shipment->tracking_number }}"></x-input-input>
                                </div>

                                <div class="form-group mb-4">
                                    <x-input-label for="shipping_fee">Shipping Fee</x-input-label>
                                    <input type="number" name="shipping_fee" class="bg-gray-100 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" value="{{ $shipment->shipping_fee ?? 0 }}" required>
                                </div>

                                <div class="form-group mb-4">
                                    <x-input-label for="payment_rules">Payment Rules</x-input-label>
                                    <x-input-input type="text" name="payment_rules" value="{{ $shipment->payment_rules }}"></x-input-input>
                                </div>

                                <div class="form-group mb-4">
                                    <x-input-label for="packing_quantity">Packing Quantity</x-input-label>
                                    <x-input-input type="number" name="packing_quantity" value="{{ $shipment->packing_quantity }}"></x-input-input>
                                </div>

                                <div class="form-group">
                                    <x-input-label for="status">Shipment Status</x-input-label>
                                    <input type="text" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" name="status" value="{{ $shipment->status }}" readonly>
                                </div>
                                
                                <div class="form-group">
                                    <x-input-label for="notes">Notes</x-input-label>
                                    <textarea name="notes" class="bg-gray-100 w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white">{{ $shipment->notes }}</textarea>
                                </div>
                            </div>

                            <div class="my-6 flex-grow border-t border-gray-500 dark:border-gray-700"></div>
                            <input type='hidden' name='referrer' value='{{ url()->previous() }}'>
                            <div class="m-4">
                                <a href="{{ url()->previous() }}">
                                    <x-secondary-button type="button">Cancel</x-secondary-button>
                                </a>
                                <x-primary-button>Update Shipment</x-primary-button>
                            </div>
                        </form>

                        <div class="my-6 flex-grow border-t border-gray-500 dark:border-gray-700"></div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-company-layout>