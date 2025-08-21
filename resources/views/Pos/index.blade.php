<div class="container">
    <h3 class="mb-4">POS - Multi-item Sale</h3>

    <div class="row mb-3">
        <div class="col-md-4">
            <label>Product</label>
            <select id="product_id" class="form-select">
                <option value="">Select Product</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">{{ $product->name }} (Stock: {{ $product->quantity }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label>Quantity</label>
            <input type="number" id="quantity" class="form-control" min="1" value="1">
        </div>
        <div class="col-md-2">
            <label>Unit Price</label>
            <input type="number" id="price" class="form-control" step="0.01" min="0">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button id="add-item" class="btn btn-primary w-100">Add Item</button>
        </div>
    </div>

    <table class="table table-bordered" id="cart-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Grand Total:</th>
                <th id="grand-total">0.00</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <div class="mb-3">
        <label>Payment Method</label>
        <select id="payment_method" class="form-select">
            <option value="">Select Payment Method</option>
            <option value="Cash">Cash</option>
            <option value="Advance">Advance</option>
            <option value="Standard Bank">Standard Bank</option>
            <option value="National Bank">National Bank</option>
            <option value="Airtel Money">Airtel Money</option>
            <option value="Mpamba">Mpamba</option>
        </select>
    </div>

    <button id="checkout" class="btn btn-success">Complete Sale</button>
</div>

<script>
let cart = [];

function updateCart() {
    let tbody = $('#cart-table tbody').empty();
    let grandTotal = 0;

    cart.forEach((item, index) => {
        let rowTotal = item.quantity * item.price;
        grandTotal += rowTotal;
        tbody.append(`
            <tr>
                <td>${item.name}</td>
                <td>${item.quantity}</td>
                <td>${item.price.toFixed(2)}</td>
                <td>${rowTotal.toFixed(2)}</td>
                <td><button class="btn btn-danger btn-sm remove-item" data-index="${index}">Remove</button></td>
            </tr>
        `);
    });

    $('#grand-total').text(grandTotal.toFixed(2));
}

$('#product_id').change(function() {
    let price = $(this).find(':selected').data('price') || 0;
    $('#price').val(price);
});

$('#add-item').click(function(e) {
    e.preventDefault();
    let productId = $('#product_id').val();
    let productName = $('#product_id option:selected').text();
    let qty = parseInt($('#quantity').val());
    let price = parseFloat($('#price').val());

    if (!productId || qty <= 0 || price < 0) return alert('Select product, quantity & price');

    cart.push({product_id: productId, name: productName, quantity: qty, price: price});
    updateCart();
});

$(document).on('click', '.remove-item', function() {
    let index = $(this).data('index');
    cart.splice(index, 1);
    updateCart();
});

// Checkout
$('#checkout').click(function() {
    let paymentMethod = $('#payment_method').val();
    if (!paymentMethod) return alert('Select a payment method');
    if (cart.length === 0) return alert('Add at least one item');

    $.ajax({
        url: "{{ route('pos.store') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            items: cart,
            payment_method: paymentMethod
        },
        success: function(response) {
            alert('Sale completed!');
            window.open('/pos/receipt/' + response.sale_id, '_blank');
            cart = [];
            updateCart();
            $('#payment_method').val('');
        },
        error: function() {
            alert('Failed to complete sale');
        }
    });
});
</script>
