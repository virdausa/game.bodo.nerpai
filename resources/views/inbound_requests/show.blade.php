<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h1 class="text-2xl font-bold mb-6">Inbound Request Details</h1>
                    <div class="mb-3 mt-1 flex-grow border-t border-gray-300 dark:border-gray-700"></div>
                    <div
                        class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                        <p class="text-sm text-gray-500 dark:text-gray-300">Warehouse</p>
                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $inboundRequest->warehouse->name }}
                        </p>
                    </div>
                    <div
                        class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600">
                        <p class="text-sm text-gray-500 dark:text-gray-300">Status</p>
                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $inboundRequest->status }}
                        </p>
                    </div>
                    
                    <div class="mb-3 mt-1 flex-grow border-t border-gray-300 dark:border-gray-700"></div>
                    <h3 class="text-lg font-bold my-3">Products</h3>
                    <ul>
                        @foreach ($inboundRequest->purchase->products as $product)
                            @if(isset($inboundRequest->requested_quantities[$product->id]) && $inboundRequest->requested_quantities[$product->id] > 0)
                                <li>
                                    <strong>{{ $product->name }}</strong><br>
                                    Requested Quantity: {{ $inboundRequest->requested_quantities[$product->id] ?? 0 }}<br>
                                    Received Quantity: {{ $inboundRequest->received_quantities[$product->id] ?? 0 }}
                                </li>
                            @endif
                        @endforeach
                    </ul>

                    @if ($inboundRequest->status === 'Ready to Complete')
                        <a href="{{ route('inbound_requests.complete', $inboundRequest->id) }}"
                            class="btn btn-primary">Complete Inbound Request</a>
                    @else
                        <p>This inbound request is {{ $inboundRequest->status }} and cannot be modified.</p>
                    @endif

                    <a href="{{ route('inbound_requests.index') }}" class="btn btn-secondary">Back to Inbound
                        Requests</a>
                    @endsection