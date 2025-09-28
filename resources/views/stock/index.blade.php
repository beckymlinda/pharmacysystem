<div class="container mt-4">
    <h3 class="mb-4 text-primary">Stock Management</h3>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Price (MK)</th>
                    <th>Expiry Date</th>
                    <th>Days Left</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr data-id="{{ $product->id }}">
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category ?? 'N/A' }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ number_format($product->selling_price, 2) }}</td>
                    <td>{{ $product->expiry_date ?? 'N/A' }}</td>
                    <td>{{ $product->days_until_expiry ?? 'N/A' }}</td>
                    <td>
                        

                        <button class="btn btn-sm btn-warning adjust-stock-btn" 
                            data-product-id="{{ $product->id }}"
                            data-product-name="{{ $product->name }}"
                            data-current-qty="{{ $product->quantity }}"
                            data-current-price="{{ $product->selling_price }}">
                            <i class="bi bi-pencil-square"></i> Adjust
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<style>



    /* Stock Management Page Custom Styles */

/* Page heading */
h3.text-primary {
    font-weight: 600;
    letter-spacing: 0.5px;
    border-bottom: 3px solid #52074f; /* your theme color */
    padding-bottom: 6px;
    margin-bottom: 20px;
}

/* Table */
.table {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    background: #fff;
}

.table thead {
    background: #f8f9fa;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.9rem;
    letter-spacing: 0.4px;
}

.table tbody tr:hover {
    background-color: #f3f0f7; /* subtle purple hover */
    transition: background 0.2s ease-in-out;
}

/* Action buttons */
.btn-info {
    background-color: #17a2b8;
    border: none;
    color: #fff;
}

.btn-info:hover {
    background-color: #138496;
    color: #fff;
}

.btn-warning {
    background-color: #dd8027;
    border: none;
    color: #fff;
}

.btn-warning:hover {
    background-color: #c56f1d;
    color: #fff;
}

/* No products row */
.table td.text-center {
    font-style: italic;
    color: #888;
}

/* Modal */
.modal-content {
    border-radius: 10px;
    border: none;
    box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}

.modal-header {
    border-bottom: none;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

.modal-footer {
    border-top: none;
}

#adjust-product-name {
    font-size: 1.1rem;
    margin-bottom: 12px;
}

</style>
