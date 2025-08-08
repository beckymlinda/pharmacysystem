<div class="container mt-4">
    <h4>Edit Sale</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('sales.update', $sale->id) }}" method="POST" class="row g-3">
        @csrf
        @method('PUT')

        <div class="col-md-4">
            <label for="product_id" class="form-label">Product</label>
            <select name="product_id" class="form-select" required>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ $sale->product_id == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" value="{{ $sale->quantity }}" class="form-control" min="1" required>
        </div>

        <div class="col-md-2">
            <label for="price" class="form-label">Price (MK)</label>
            <input type="number" step="0.01" name="price" value="{{ $sale->price }}" class="form-control" required>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-success w-100"><i class="bi bi-check-circle"></i> Save</button>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <a href="#" onclick="loadContent('{{ route('sales.index') }}')" class="btn btn-secondary w-100">
                <i class="bi bi-arrow-left-circle"></i> Back
            </a>
        </div>
    </form>
</div>
