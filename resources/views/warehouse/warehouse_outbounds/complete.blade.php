@extends('layouts.app')

@section('content')
    <h1>Complete Outbound Request</h1>

    <form action="{{ route('outbound_requests.verifyCompletion', $outboundRequest->id) }}" method="POST">
        @csrf
        @method('PUT')

        <h3>Products for Verification</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Requested Quantity</th>
                    <th>Locations (Room, Rack, Quantity)</th>
                    <th>Customer Received Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($outboundRequest->requested_quantities as $productId => $requestedQuantity)
                    <tr>
                        <td>{{ $outboundRequest->sales->products->find($productId)->name }}</td>
                        <td>{{ $requestedQuantity }}</td>
                        <td>
                            @foreach ($outboundRequest->locations->where('product_id', $productId) as $location)
                                <div>
                                    Room: {{ $location->room }}, Rack: {{ $location->rack }},
                                    Quantity: {{ $location->quantity }}
                                </div>
                            @endforeach
                        </td>
                        <td>
                            <input type="number" name="received_quantities[{{ $productId }}]" min="0" class="form-control" value="{{ $requestedQuantity }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-success">Verify & Complete</button>
        <a href="{{ route('outbound_requests.show', $outboundRequest->id) }}" class="btn btn-secondary">Back</a>
    </form>
@endsection
