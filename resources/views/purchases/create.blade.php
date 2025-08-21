<div class="container">
    <h3 class="mb-4">Record New Purchase</h3>

    <form id="create-purchase-form" action="{{ route('purchases.store') }}" method="POST" class="ajax-form">
        @csrf
        <div class="mb-3">
            <label for="product_id" class="form-label">Product</label>
            <select name="product_id" id="product_id" class="form-select" required>
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->quantity }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price per Unit (MK)</label>
            <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" required>
        </div>

        <div class="mb-3">
            <label for="total_cost" class="form-label">Total Cost (MK)</label>
            <input type="number" name="total_cost" id="total_cost" class="form-control" step="0.01" min="0" readonly>
        </div>

        <div class="mb-3">
            <label for="supplier" class="form-label">Supplier</label>
            <input type="text" name="supplier" id="supplier" class="form-control">
        </div>

        <div class="mb-3">
            <label for="purchase_date" class="form-label">Purchase Date</label>
            <input type="date" name="purchase_date" id="purchase_date" class="form-control" value="{{ date('Y-m-d') }}">
        </div>

        <div class="mb-3">
            <label for="expiry_date" class="form-label">Expiry Date</label>
            <input type="date" name="expiry_date" id="expiry_date" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Save Purchase</button>
    </form>
</div>

<script>
    // Auto-calculate total cost
    $('#quantity, #price').on('input', function() {
        let qty = parseFloat($('#quantity').val()) || 0;
        let price = parseFloat($('#price').val()) || 0;
        $('#total_cost').val((qty * price).toFixed(2));
    });

    // Handle form via AJAX
    $(document).on('submit', '#create-purchase-form', function(e) {
        e.preventDefault();
        let form = $(this);

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                // Reload index after creation
                $.get("{{ route('purchases.index') }}", function(data){
                    $('#main-content').html(data);
                    $('<div class="alert alert-success mt-2">Purchase recorded successfully!</div>')
                        .prependTo('#main-content')
                        .delay(3000)
                        .fadeOut();
                });
            },
            error: function(xhr) {
                alert('Failed to save purchase. Check inputs.');
            }
        });
    });
</script>
