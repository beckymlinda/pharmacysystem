<div class="container mt-4">
    <h4>Manage Purchases</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Add Purchase Form -->
    <form action="{{ route('purchases.store') }}" method="POST" class="row g-2 mb-4">
        @csrf
        <div class="col-md-3">
            <select name="product_id" class="form-select" required>
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="quantity" class="form-control" placeholder="Qty" required>
        </div>
        <div class="col-md-2">
            <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required>
        </div>
        <div class="col-md-2">
            <input type="text" name="supplier" class="form-control" placeholder="Supplier">
        </div>
        <div class="col-md-2">
            <input type="date" name="purchase_date" class="form-control" required>
        </div>
        <div class="col-md-1">
            <button class="btn btn-primary w-100"><i class="bi bi-plus-circle"></i></button>
        </div>
    </form>

    <!-- Purchase Table -->
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
                <th>Supplier</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($purchases as $purchase)
            <tr>
                <td>{{ $purchase->product->name ?? 'N/A' }}</td>
                <td>{{ $purchase->quantity }}</td>
                <td>MK {{ number_format($purchase->price, 2) }}</td>
                <td>MK {{ number_format($purchase->total_cost, 2) }}</td>
                <td>{{ $purchase->supplier }}</td>
                <td>{{ $purchase->purchase_date }}</td>
                <td class="d-flex gap-2">
    <button class="btn btn-sm btn-warning ajax-link" 
            data-url="{{ route('purchases.edit', $purchase->id) }}">
        <i class="bi bi-pencil"></i> Edit
    </button>

    <button class="btn btn-sm btn-info ajax-link" 
            data-url="{{ route('purchases.show', $purchase->id) }}">
        <i class="bi bi-eye"></i> View
    </button>

    <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST">
        @csrf @method('DELETE')
        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
    </form>
</td>


            </tr>
        @endforeach
        </tbody>
    </table>
</div>
