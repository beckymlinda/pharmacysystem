<div class="container">
    <h3 class="mb-4">Edit Purchase #{{ $purchase->id }}</h3>

    <form id="edit-purchase-form" action="{{ route('purchases.update', $purchase->id) }}" 
          method="POST" class="ajax-form">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="product_id" class="form-label">Product</label>
            <select name="product_id" id="product_id" class="form-select" required>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" 
                        {{ $purchase->product_id == $product->id ? 'selected' : '' }}>
                        {{ $product->name }} (Stock: {{ $product->quantity }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" 
                   value="{{ $purchase->quantity }}" min="1" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price per Unit (MK)</label>
            <input type="number" name="price" id="price" class="form-control" 
                   value="{{ $purchase->price }}" step="0.01" min="0" required>
        </div>

        <div class="mb-3">
            <label for="total_cost" class="form-label">Total Cost (MK)</label>
            <input type="number" name="total_cost" id="total_cost" class="form-control" 
                   value="{{ $purchase->total_cost }}" step="0.01" min="0" readonly>
        </div>

        <div class="mb-3">
            <label for="supplier" class="form-label">Supplier</label>
            <input type="text" name="supplier" id="supplier" class="form-control" 
                   value="{{ $purchase->supplier }}">
        </div>

        <div class="mb-3">
            <label for="purchase_date" class="form-label">Purchase Date</label>
            <input type="date" name="purchase_date" id="purchase_date" class="form-control" 
                   value="{{ $purchase->purchase_date }}">
        </div>

        <div class="mb-3">
            <label for="expiry_date" class="form-label">Expiry Date</label>
            <input type="date" name="expiry_date" id="expiry_date" class="form-control" 
                   value="{{ $purchase->expiry_date }}">
        </div>

        <button type="submit" class="btn btn-success">Update Purchase</button>
    </form>
</div>
<style>

    .table-info {
    background-color: #d1ecf1 !important; /* light cyan */
    transition: background-color 0.5s ease;
}

</style>

<script>
    // Auto-calculate total cost
    $('#quantity, #price').on('input', function() {
        let qty = parseFloat($('#quantity').val()) || 0;
        let price = parseFloat($('#price').val()) || 0;
        $('#total_cost').val((qty * price).toFixed(2));
    });

    // AJAX submission for edit form
    $(document).on('submit', '#edit-purchase-form', function(e) {
        e.preventDefault();
        let form = $(this);

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                $.get("{{ route('purchases.index') }}", function(data){
                    $('#main-content').html(data);
                    $('<div class="alert alert-success mt-2">Purchase updated successfully!</div>')
                        .prependTo('#main-content')
                        .delay(3000)
                        .fadeOut();
                });
            },
            error: function(xhr) {
                alert('Failed to update purchase. Check inputs.');
            }
        });
    });

    success: function(response) {
    $.get("{{ route('purchases.index') }}", function(data){
        $('#main-content').html(data);

        // Highlight the recently edited row
        let editedId = response.edited_id;
        let row = $('tr').filter(function() {
            return $(this).find('td:first').text() == editedId;
        });
        row.addClass('table-info'); // Bootstrap light-blue highlight
        setTimeout(() => row.removeClass('table-info'), 4000); // remove after 4s

        $('<div class="alert alert-success mt-2">Purchase updated successfully!</div>')
            .prependTo('#main-content')
            .delay(3000)
            .fadeOut();
    });
}

</script>
