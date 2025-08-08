<div class="container mt-4">
    <h4>Point of Sale</h4>

    <div class="row g-3 align-items-center mb-3">
        <div class="col-md-6">
            <select id="productSelect" class="form-select">
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                        {{ $product->name }} - MK {{ number_format($product->price, 2) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" id="productQty" class="form-control" min="1" value="1" />
        </div>
        <div class="col-md-2">
            <button id="addToCart" class="btn btn-primary w-100"><i class="bi bi-plus-circle"></i> Add</button>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Product</th>
                <th>Price (MK)</th>
                <th>Quantity</th>
                <th>Subtotal (MK)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="cartItems">
            <!-- Cart items will appear here -->
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Total:</th>
                <th id="cartTotal">MK 0.00</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <button id="finalizeSale" class="btn btn-success">Complete Sale</button>
</div>

<script>
    let cart = [];

    function renderCart() {
        let $cartItems = $('#cartItems');
        $cartItems.empty();
        let total = 0;

        cart.forEach((item, index) => {
            let subtotal = item.price * item.quantity;
            total += subtotal;

            $cartItems.append(`
                <tr>
                    <td>${item.name}</td>
                    <td>MK ${item.price.toFixed(2)}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm update-qty" data-index="${index}" value="${item.quantity}" min="1" />
                    </td>
                    <td>MK ${subtotal.toFixed(2)}</td>
                    <td>
                        <button class="btn btn-sm btn-danger remove-item" data-index="${index}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
        });

        $('#cartTotal').text('MK ' + total.toFixed(2));
    }

    $('#addToCart').click(function () {
        let productId = $('#productSelect').val();
        let productName = $('#productSelect option:selected').text();
        let price = parseFloat($('#productSelect option:selected').data('price'));
        let quantity = parseInt($('#productQty').val());

        if (!productId) return alert('Please select a product.');
        if (quantity < 1) return alert('Quantity must be at least 1.');

        let existingIndex = cart.findIndex(item => item.productId == productId);
        if (existingIndex >= 0) {
            cart[existingIndex].quantity += quantity;
        } else {
            cart.push({ productId, name: productName, price, quantity });
        }

        renderCart();
    });

    $(document).on('click', '.remove-item', function () {
        let index = $(this).data('index');
        cart.splice(index, 1);
        renderCart();
    });

    $(document).on('input', '.update-qty', function () {
        let index = $(this).data('index');
        let newQty = parseInt($(this).val());
        if (newQty < 1) {
            alert("Quantity must be at least 1");
            $(this).val(cart[index].quantity);
            return;
        }
        cart[index].quantity = newQty;
        renderCart();
    });

    $('#finalizeSale').click(function () {
        if (cart.length === 0) return alert('Cart is empty.');

        $.ajax({
            url: "{{ route('sales.store') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                items: cart
            },
            success: function (response) {
                alert('Sale completed successfully!');
                cart = [];
                renderCart();
            },
            error: function () {
                alert('Error completing sale. Please try again.');
            }
        });
    });
</script>
 