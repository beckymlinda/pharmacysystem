<div class="container">
    <h3 class="mb-4">All Purchases</h3>

    <a href="{{ route('purchases.create') }}" class="btn btn-primary mb-3 ajax-link" data-url="{{ route('purchases.create') }}">Record New Purchase</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Batch</th>
                <th>Expiry Date</th>
                <th>Supplier</th>
                <th>Quantity</th>
                <th>Price per Unit (MK)</th>
                <th>Total Cost (MK)</th>
                <th>Purchase Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchases as $purchase)
            @php
                $today = \Carbon\Carbon::today();
                $expiryClass = '';
                if ($purchase->expiry_date) {
                    if ($purchase->expiry_date < $today) {
                        $expiryClass = 'table-danger'; // expired
                    } elseif ($purchase->expiry_date <= $today->copy()->addDays(30)) {
                        $expiryClass = 'table-warning'; // expiring soon
                    }
                }
            @endphp
            <tr class="{{ $expiryClass }}">
                <td>{{ $purchase->id }}</td>
                <td>{{ $purchase->product->name ?? 'N/A' }}</td>
                <td>{{ $purchase->batch_number ?? '-' }}</td>
                <td>{{ $purchase->expiry_date ? \Carbon\Carbon::parse($purchase->expiry_date)->format('d M Y') : '-' }}</td>
                <td>{{ $purchase->supplier ?? '-' }}</td>
                <td>{{ $purchase->quantity }}</td>
                <td>{{ number_format($purchase->price, 2) }}</td>
                <td>{{ number_format($purchase->total_cost, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</td>
                <td><a href="#" class="btn btn-sm btn-warning ajax-link" 
   data-url="{{ route('purchases.edit', $purchase->id) }}">
    Edit
</a>

    <form action="{{ route('purchases.destroy', $purchase->id) }}" 
          method="POST" class="d-inline delete-form">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-danger">Delete</button>
    </form>
</td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
