<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Product Details</h4>
        <button class="btn btn-sm btn-secondary" onclick="loadContent('{{ route('products.index') }}')">
            <i class="bi bi-arrow-left"></i> Back to Products
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title mb-3">{{ $product->name }}</h5>
            <p><strong>Category:</strong> {{ $product->category ?? 'N/A' }}</p>
            <p><strong>Quantity:</strong> {{ $product->quantity }}</p>
            <p><strong>Price:</strong> MK {{ number_format($product->selling_price, 2) }}</p>

            <div class="mt-3 d-flex gap-2">
                <button class="btn btn-success" onclick="loadContent('{{ route('products.edit', $product->id) }}')">
                    <i class="bi bi-pencil-square"></i> Edit
                </button>

                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
