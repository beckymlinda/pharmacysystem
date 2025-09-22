<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>POS - Multi-item Sale</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    /* Body & container tweaks */
    body {
      background-color: #e9f7f2; /* light greenish background */
      padding-top: 20px; /* move content a bit up */
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container {
      max-width: 1200px;
    }

    h3 {
      font-weight: 700;
      color: #0d6efd; /* Bootstrap blue */
      margin-bottom: 30px;
      text-align: center;
    }

    /* Card styling */
    .card {
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 20px rgba(0,0,0,0.15);
    }

    .card-header {
      font-weight: 600;
      font-size: 1.15rem;
      background: linear-gradient(90deg, #0d6efd, #0dcaf0);
      color: white;
      border-radius: 10px 10px 0 0;
      padding: 10px 15px;
      box-shadow: inset 0 -2px 4px rgba(0,0,0,0.1);
    }

    /* Product search list */
    #product-list {
      z-index: 2000;
      background: white;
      border-radius: 5px;
      border: 1px solid #ddd;
    }

    #product-list li:hover {
      background-color: #0dcaf0;
      color: white;
      cursor: pointer;
    }

    /* Tables */
    table th, table td {
      vertical-align: middle;
      text-align: center;
    }

    table tbody tr:hover {
      background-color: #d1ecf1; /* light blue hover */
    }

    /* Buttons */
    #add-item, #checkout {
      border-radius: 8px;
      font-weight: 600;
      transition: transform 0.2s;
    }

    #add-item:hover, #checkout:hover, #add-payment:hover {
      transform: translateY(-2px);
    }

    #checkout {
      background: linear-gradient(90deg, #0dcaf0, #198754); /* blue to light green gradient */
      border: none;
    }

    #checkout:hover {
      background: linear-gradient(90deg, #198754, #0dcaf0);
    }

    #add-payment {
      border-radius: 6px;
      font-weight: 500;
      background-color: #e0f7ea;
      color: #198754;
      border: 1px solid #198754;
    }

    #add-payment:hover {
      background-color: #198754;
      color: white;
    }

    /* Inputs */
    input.form-control {
      border-radius: 6px;
      box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
    }

    /* Responsive tweaks */
    @media (max-width: 767px) {
      .card-header {
        font-size: 1rem;
      }
      h3 {
        font-size: 1.5rem;
      }
    }

  </style>
</head>
<body>

<div class="container py-2">
  <h3>POS - Multi-item Sale</h3>

  <div class="row g-4">
    <!-- Left Column: Product Search -->
    <div class="col-md-6">
      <div class="card p-3">
        <div class="card-header mb-3">Add Product</div>

        <div class="mb-3 position-relative">
    <label class="form-label">Product</label>
    <input type="search" id="product-search" class="form-control" placeholder="Search product..." required>
    <input type="hidden" id="product_id" required>
    <ul id="product-list" class="list-group" style="max-height:150px; overflow-y:auto; position:absolute;"></ul>
</div>
<div class="row g-3">
    <div class="col-3">
        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" id="quantity" class="form-control" min="1" value="1" required>
        </div>
    </div>

    <div class="col-3">
        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" id="price" class="form-control" step="0.01" min="0.01" value="0" required>
        </div>
    </div>
</div>


        <button id="add-item" class="btn btn-primary w-100">Add Item</button>
      </div>
    </div>

    <!-- Right Column: Cart Table -->
    <div class="col-md-6">
      <div class="card p-3">
        <div class="card-header mb-3">Cart</div>
        <table class="table table-bordered table-hover mb-0" id="cart-table">
          <thead class="table-light">
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
      </div>
    </div>

    <!-- Full-width Column: Payments -->
    <div class="col-12">
      <div class="card p-3">
        <div class="card-header mb-3">Payments</div>

        <button type="button" id="add-payment" class="btn btn-outline-primary btn-sm mb-3">
          + Add Payment Method
        </button>

        <table class="table table-bordered mb-3" id="payment-table">
          <thead class="table-light">
            <tr>
              <th>Method</th>
              <th>Amount</th>
              <th></th>
            </tr>
          </thead>
          <tbody></tbody>
          <tfoot>
            <tr>
              <th class="text-end">Total Paid:</th>
              <th id="total-paid">0.00</th>
              <th></th>
            </tr>
          </tfoot>
        </table>

        <div class="text-end">
          <button id="checkout" class="btn btn-lg px-4">Complete Sale</button>
        </div>
      </div>
    </div>
  </div>
</div>
 <script>
/* ================== PRODUCTS DATA ================== */
// Make sure this is declared only once globally
window.products = [
    @foreach($products as $product)
        { id: {{ $product->id }}, name: "{{ $product->name }}", stock: {{ $product->quantity }}, price: {{ $product->selling_price }} },
    @endforeach
];

/* ================== CART & PAYMENTS ================== */
window.cart = [];
window.payments = [];

/* ================== SEARCH FUNCTIONALITY ================== */
$(document).on('input', '#product-search', function() {
    const filter = $(this).val().toLowerCase();
    const productList = $('#product-list');
    productList.empty();

    const matches = window.products.filter(p => p.name.toLowerCase().includes(filter));

    matches.forEach(p => {
        const li = $('<li>')
            .addClass('list-group-item list-group-item-action')
            .text(`${p.name} (Stock: ${p.stock})`)
            .css('cursor', 'pointer')
            .data('id', p.id)
            .data('price', p.price);
        productList.append(li);
    });
});

// Click suggestion
$(document).on('click', '#product-list li', function() {
    const li = $(this);
    $('#product-search').val(li.text());
    $('#product_id').val(li.data('id'));
    $('#price').val(li.data('price'));
    $('#product-list').empty();
});

// Close suggestions when clicking outside
$(document).on('click', function(e) {
    if (!$(e.target).is('#product-search') && !$(e.target).is('#product-list') && !$.contains($('#product-list')[0], e.target)) {
        $('#product-list').empty();
    }
});

/* ================== CART FUNCTIONALITY ================== */
function updateCart() {
    let tbody = $('#cart-table tbody').empty();
    let grandTotal = 0;

    window.cart.forEach((item, index) => {
        const rowTotal = item.quantity * item.price;
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
// Add item
$(document).on('click', '#add-item', function() {
    const productId = parseInt($('#product_id').val()); // convert to number
    const product = window.products.find(p => p.id === productId); // strict comparison
    const qty = parseInt($('#quantity').val()) || 0;
    const price = parseFloat($('#price').val()) || 0;
 

    window.cart.push({ product_id: productId, name: product.name, quantity: qty, price });
    updateCart();

     

    // Reset inputs
    $('#product-search').val('');
    $('#product_id').val('');
    $('#quantity').val(1);
    $('#price').val(0);
});

// Remove item
$(document).on('click', '.remove-item', function() {
    const index = $(this).data('index');
    window.cart.splice(index, 1);
    updateCart();
});

/* ================== PAYMENTS ================== */
$(document).on('click', '#add-payment', function() {
    $('#payment-table tbody').append(`
        <tr>
            <td>
                <select class="form-select payment-method">
                    <option value="">Select Method</option>
                    <option value="Cash">Cash</option>
                    <option value="Advance">Advance</option>
                    <option value="Standard Bank">Standard Bank</option>
                    <option value="National Bank">National Bank</option>
                    <option value="Airtel Money">Airtel Money</option>
                    <option value="Mpamba">Mpamba</option>
                </select>
            </td>
            <td>
                <input type="number" class="form-control payment-amount" step="0.01" min="0" value="0">
            </td>
            <td>
                <button class="btn btn-danger btn-sm remove-payment">X</button>
            </td>
        </tr>
    `);
});

// Remove payment
$(document).on('click', '.remove-payment', function() {
    $(this).closest('tr').remove();
    calculateTotalPaid();
});

// Recalculate total paid
$(document).on('input change', '.payment-method, .payment-amount', function() {
    calculateTotalPaid();
});

function calculateTotalPaid() {
    let total = 0;
    window.payments = [];
    $('#payment-table tbody tr').each(function() {
        const method = $(this).find('.payment-method').val();
        const amount = parseFloat($(this).find('.payment-amount').val()) || 0;
        if (method && amount > 0) {
            window.payments.push({ method, amount });
            total += amount;
        }
    });
    $('#total-paid').text(total.toFixed(2));
}

/* ================== CHECKOUT ================== */
$(document).on('click', '#checkout', function() {
    calculateTotalPaid();

    if (window.cart.length === 0) return alert('Add at least one item');

    let grandTotal = parseFloat($('#grand-total').text());
    let totalPaid = window.payments.reduce((sum, p) => sum + p.amount, 0);

    if (window.payments.length === 0) return alert('Add at least one payment method');
    if (totalPaid < grandTotal) return alert('Paid amount is less than grand total!');
    if (totalPaid > grandTotal) return alert('Paid amount exceeds grand total!');

    $.ajax({
        url: "{{ route('pos.store') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            items: window.cart,
            payments: window.payments
        },
        success: function(response) {
            alert('Sale completed!');
            window.open('/pos/receipt/' + response.sale_id, '_blank');
            window.cart = [];
            window.payments = [];
            updateCart();
            $('#payment-table tbody').empty();
            $('#total-paid').text('0.00');
        },
        error: function() {
            alert('Failed to complete sale');
        }
    });
});
</script>
