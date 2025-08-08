 
<div class="container mt-4">
    <h4>Edit Purchase</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

<form id="edit-purchase-form" action="{{ route('purchases.update', $purchase->id) }}" method="POST" class="row g-2">        @csrf
        @method('PUT')

        <div class="col-md-3">
            <select name="product_id" class="form-select" required>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ $purchase->product_id == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <input type="number" name="quantity" class="form-control" value="{{ $purchase->quantity }}" required>
        </div>

        <div class="col-md-2">
            <input type="number" step="0.01" name="price" class="form-control" value="{{ $purchase->price }}" required>
        </div>

        <div class="col-md-2">
            <input type="text" name="supplier" class="form-control" value="{{ $purchase->supplier }}">
        </div>

        <div class="col-md-2">
            <input type="date" name="purchase_date" class="form-control" value="{{ $purchase->purchase_date }}">
        </div>

        <div class="col-md-1">
            <button class="btn btn-success w-100"><i class="bi bi-check-circle"></i></button>
        </div>
    </form>

    <div class="mt-3">
        <a href="{{ route('purchases.index') }}" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>
