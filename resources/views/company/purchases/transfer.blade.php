@extends('layouts.app')

@section('content')
    <h1>Transfer Products to Warehouse</h1>
    <form action="{{ route('purchases.transfer.store', $purchase->id) }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="warehouse">Select Warehouse</label>
            <select name="warehouse_id" class="form-control" required>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>

        <h2>Products in Purchase</h2>
        @foreach($purchase->products as $product)
            <div class="form-group">
                <label>{{ $product->name }}</label>
                <input type="number" name="products[{{ $product->id }}]" class="form-control" placeholder="Enter quantity to transfer" min="0" max="{{ $product->pivot->quantity }}" required>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary">Transfer to Warehouse</button>
    </form>
@endsection
