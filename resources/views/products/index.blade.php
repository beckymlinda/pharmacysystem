{{-- resources/views/products/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manage Products | EDUC Pharmacy</title>

  {{-- Bootstrap CSS --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Bootstrap Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  {{-- jQuery (for simplicity; you may replace with alpine/vue) --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
    /* small visual polish */
    .card { border-radius: 12px; }
    .low-stock { color: #b00; font-weight: 700; }
    .expiring-soon { color: #d97706; font-weight: 700; } /* amber */
    .badge-expired { background: #dc3545; color: #fff; }
    .badge-expiring { background: #ffc107; color: #212529; }
    .table thead th { vertical-align: middle; }
  </style>
</head>
<body>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">Manage Products</h4>
    <div>
        <a href="{{ route('products.export', ['type' => 'csv']) }}" class="btn btn-sm btn-success">
            <i class="bi bi-file-earmark-excel"></i> Export CSV
        </a>
        <a href="{{ route('products.export', ['type' => 'pdf']) }}" class="btn btn-sm btn-danger ms-2">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF
        </a>
        <button class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="bi bi-plus-circle"></i> Add Product
        </button>
    </div>
</div>

    <!-- Success / Error placeholder -->
    <div id="product-messages"></div>
@if ($lowStockProducts->count() > 0)
    <div class="alert alert-warning mb-3" id="low-stock-alert" style="cursor:pointer">
        <i class="bi bi-exclamation-triangle"></i>
        <strong>Low Stock Alert:</strong> {{ $lowStockProducts->count() }} product(s) need restocking. 
        <span class="text-decoration-underline">Click to view</span>
    </div>
@endif

    {{-- PRODUCTS TABLE CARD --}}
   <div class="card mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle" id="products-table">
           <thead class="table-light">
<tr>
    <th>Name</th>
    <th>Brand</th>
    <th>Category</th>
    <th>Qty</th>
    <th>Alert Qty</th>
    <th>Order Price</th>
    <th>Selling Price</th>
    <th>Profit</th> {{-- only shows profit + "View" link --}}
    <th>Expiry</th>
    <th>Seller</th>
    <th>Purchases</th> {{-- "View" button --}}
    <th class="text-center">Actions</th>
</tr>
</thead>

<tbody id="products-tbody">
@foreach($products as $product)
@php
    $isLow = $product->quantity <= $product->alert_quantity;
    $expiryBadge = null;
    if($product->expiry_date) {
        $days = now()->diffInDays(\Illuminate\Support\Carbon::parse($product->expiry_date), false);
        if($days < 0) $expiryBadge = 'expired';
        elseif($days <= 30) $expiryBadge = 'soon';
        else $expiryBadge = 'ok';
    }

    $orderPrice = $product->order_price ?? 0;
    $sellingPrice = $product->selling_price ?? 0;
    $profit = $sellingPrice - $orderPrice;
    $margin = $orderPrice > 0 ? ($profit / $orderPrice) * 100 : 0;
@endphp

<tr data-id="{{ $product->id }}">
    <td>{{ $product->name }}</td>
    <td>{{ $product->brand ?? '-' }}</td>
    <td>{{ $product->category ?? '-' }}</td>
    <td class="{{ $isLow ? 'low-stock' : '' }}">{{ $product->quantity }}</td>
    <td>{{ $product->alert_quantity ?? 0 }}</td>
    <td>{{ number_format($orderPrice, 2) }}</td>
    <td>{{ number_format($sellingPrice, 2) }}</td>

    {{-- Profit with "View" link --}}
    <td>
        {{ number_format($profit, 2) }}
        <a href="#"
           class="ms-2 text-decoration-underline small view-profit"
           data-bs-toggle="modal"
           data-bs-target="#profitModal"
           data-profit="{{ number_format($profit,2) }}"
           data-margin="{{ number_format($margin,2) }}">
           View
        </a>
    </td>

    {{-- Expiry --}}
    <td>
        @if(!$product->expiry_date)
            <span class="text-muted">N/A</span>
        @else
            {{ \Carbon\Carbon::parse($product->expiry_date)->format('Y-m-d') }}
            @if($expiryBadge === 'expired')
                <span class="badge badge-expired ms-1">Expired</span>
            @elseif($expiryBadge === 'soon')
                <span class="badge badge-expiring ms-1">Expiring</span>
            @endif
        @endif
    </td>

    <td>{{ $product->seller ?? '-' }}</td>

    {{-- Purchases modal button --}}
    <td>
        <button
            class="btn btn-sm btn-secondary view-purchases-btn"
            data-product-id="{{ $product->id }}"
            data-bs-toggle="modal"
            data-bs-target="#purchaseModal">
            View
        </button>
    </td>

    {{-- Actions --}}
    <td class="text-end">
        <div class="btn-group" role="group" aria-label="actions">
            <button class="btn btn-sm btn-warning edit-btn" data-url="{{ route('products.edit', $product->id) }}">
                <i class="bi bi-pencil"></i>
            </button>
            <button class="btn btn-sm btn-info view-btn" data-url="{{ route('products.show', $product->id) }}">
                <i class="bi bi-eye"></i>
            </button>
            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger delete-btn">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </td>
</tr>
@endforeach
</tbody>

            </table>
        </div>
    </div>
</div>

</div>

{{-- ---------------------------
     ADD PRODUCT MODAL
   --------------------------- --}}
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="add-product-form" action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label">Product Name</label>
                    <input name="name" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <input name="category" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Brand</label>
                    <input name="brand" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Seller</label>
                    <input name="seller" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Alert Quantity</label>
                    <input name="alert_quantity" type="number" min="0" class="form-control" value="0">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Order Price</label>
                    <input name="order_price" type="number" step="0.01" min="0" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Selling Price</label>
                    <input name="selling_price" type="number" step="0.01" min="0" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Qty (initial)</label>
                    <input name="quantity" type="number" min="0" class="form-control" value="0" required>
                </div>

                <div class="col-md-6 mt-2">
                    <label class="form-label">Expiry Date (optional)</label>
                    <input name="expiry_date" type="date" class="form-control">
                </div>

                <div class="col-md-6 mt-2">
                    <label class="form-label">Notes (optional)</label>
                    <input name="notes" type="text" class="form-control">
                </div>
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add Product</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- EDIT PRODUCT MODAL (AJAX-loaded) -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Edit Product</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Body: AJAX content will be injected here -->
      <div class="modal-body" id="edit-product-modal-content">
        <div class="text-center py-5">
          <div class="spinner-border text-primary" role="status"></div>
          <div class="mt-2">Loading product details...</div>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <!-- Save button will be part of the injected form -->
      </div>

    </div>
  </div>
</div>


 


{{-- ---------------------------
     VIEW PRODUCT MODAL (AJAX-loaded)
   --------------------------- --}}
<div class="modal fade" id="viewProductModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content" id="view-product-modal-content">
      {{-- content injected by AJAX --}}
      <div class="modal-body text-center py-5">
        <div class="spinner-border text-primary" role="status"></div>
        <div class="mt-2">Loading...</div>
      </div>
    </div>
  </div>
</div>
<!-- LOW STOCK MODAL (keep in your Blade) -->
<div class="modal fade" id="lowStockModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Low Stock Products</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="low-stock-list" class="text-center py-5">
          <div class="spinner-border text-primary"></div>
          <div class="mt-2">Loading...</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Check Low Stock Button -->
<button id="checkLowStockBtn" class="btn btn-warning">
    <i class="bi bi-exclamation-triangle"></i> Check Low Stock
</button>

<div class="modal fade" id="profitModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Profit Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <ul class="list-unstyled mb-0">
          <li><strong>Profit:</strong> <span id="p-profit"></span></li>
          <li><strong>Margin %:</strong> <span id="p-margin"></span></li>
          <li><strong>Selling (Ex. Tax):</strong> <span id="p-exctax"></span></li>
          <li><strong>Selling (Inc. Tax):</strong> <span id="p-inctax"></span></li>
          <li><strong>Tax Amount:</strong> <span id="p-tax"></span></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="purchaseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Purchase Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="purchase-modal-content" class="text-center py-5">
          <div class="spinner-border text-primary" role="status"></div>
          <div class="mt-2">Loading purchase history...</div>
        </div>
      </div>
    </div>
  </div>
</div>


<script>

    // Handle Profit View modal
$(document).on('click', '.view-profit', function() {
    $('#p-profit').text($(this).data('profit'));
    $('#p-margin').text($(this).data('margin') + '%');
    $('#p-exctax').text($(this).data('exctax'));
    $('#p-inctax').text($(this).data('inctax'));
    $('#p-tax').text($(this).data('tax'));
});

</script>
<script>
$(document).ready(function() {

    function loadLowStock() {
        const list = $('#low-stock-list');
        list.html('<div class="text-center py-5"><div class="spinner-border text-primary"></div><div class="mt-2">Loading...</div></div>');

        $.get("{{ route('products.check-low-stock') }}")
            .done(function(res) {
                if(res.count === 0) {
                    list.html('<div class="alert alert-success">✅ No products are currently low in stock.</div>');
                    return;
                }

                let html = `<table class="table table-sm table-bordered">
                                <thead><tr><th>Name</th><th>Qty</th><th>Alert Qty</th></tr></thead><tbody>`;
                res.products.forEach(p => {
                    html += `<tr>
                                <td>${p.name}</td>
                                <td class="text-danger fw-bold">${p.quantity}</td>
                                <td>${p.alert_quantity}</td>
                             </tr>`;
                });
                html += `</tbody></table>`;
                list.html(html);
            })
            .fail(function() {
                list.html('<div class="alert alert-danger">❌ Failed to load low stock products.</div>');
            });
    }

    // Open modal and load data
    $('#checkLowStockBtn, #low-stock-alert').on('click', function() {
        $('#lowStockModal').modal('show');
        loadLowStock();
    });

});
</script>


{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(function() {
    // Setup CSRF for AJAX
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Utility: show messages
    function showMessage(type, text, timeout = 4000) {
        const html = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${text}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                      </div>`;
        $('#product-messages').html(html);
        if(timeout) setTimeout(() => $('.alert').alert('close'), timeout);
    }

    // ---------- ADD PRODUCT via AJAX ----------
    $('#add-product-form').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const url = $form.attr('action');
        const data = $form.serialize();

        $.post(url, data)
            .done(function(res) {
                $('#addProductModal').modal('hide');
                $('.modal-backdrop').remove(); // remove lingering backdrop
                $form[0].reset();
                reloadProductsTable();
                showMessage('success', 'Product added successfully.');
            })
            .fail(function(xhr) {
                const err = xhr.responseJSON?.message || xhr.responseText || 'Failed to add product';
                showMessage('danger', err, 7000);
            });
    });

    // ---------- RELOAD PRODUCTS TABLE ----------
    function reloadProductsTable() {
        $.get("{{ route('products.index') }}", { _partial: 1 })
            .done(function(html) {
                const newTbody = $(html).find('#products-tbody').html();
                if(newTbody) {
                    $('#products-tbody').html(newTbody);
                } else {
                    location.reload();
                }
            })
            .fail(function() { location.reload(); });
    }

    // ---------- DELETE PRODUCT via AJAX ----------
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        if(!confirm('Are you sure you want to delete this product?')) return;

        const $form = $(this);
        const url = $form.attr('action');

        $.ajax({
            url: url,
            method: 'POST',
            data: $form.serialize(),
        }).done(function(res) {
            showMessage('success', res.message || 'Product deleted.');
            $form.closest('tr').remove();
        }).fail(function(xhr) {
            showMessage('danger', 'Delete failed: ' + (xhr.responseJSON?.message || xhr.responseText));
        });
    });

    // ---------- EDIT: load edit form in modal ----------
    $(document).on('click', '.edit-btn', function() {
        const url = $(this).data('url');
        $('#edit-product-modal-content').html(`<div class="modal-body text-center py-5"><div class="spinner-border text-primary"></div><div class="mt-2">Loading...</div></div>`);
        $('#editProductModal').modal('show');

        $.get(url)
            .done(function(html) {
                $('#edit-product-modal-content').html(html);
            }).fail(function() {
                $('#edit-product-modal-content').html('<div class="modal-body p-4">Failed to load form.</div>');
            });
    });

// ---------- SUBMIT ADD / EDIT FORM via AJAX ----------
function handleFormAjax($form, modalId) {
    const url = $form.attr('action');
    const method = $form.find('input[name=_method]').val() || 'POST';
    const data = $form.serialize();

    $.ajax({
        url: url,
        method: method,
        data: data
    }).done(function(res) {
        // Close modal
        $(`#${modalId}`).modal('hide');
        $('.modal-backdrop').remove();

        // Reload products table inside main content
        $.get("{{ route('products.index') }}", { _partial: 1 })
            .done(function(html) {
                const newTbody = $(html).find('#products-tbody').html();
                if(newTbody) {
                    $('#products-tbody').html(newTbody);
                    $('#product-messages').html(
                        `<div class="alert alert-success alert-dismissible fade show" role="alert">
                            ${res.message || 'Operation successful.'}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>`
                    );
                } else {
                    location.reload(); // fallback
                }
            });

    }).fail(function(xhr) {
        const err = xhr.responseJSON?.message || 'Operation failed';
        $('#product-messages').html(
            `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                ${err}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`
        );
    });
}

// Add Product
$('#add-product-form').on('submit', function(e) {
    e.preventDefault();
    handleFormAjax($(this), 'addProductModal');
});

// Edit Product
$(document).on('submit', '#edit-product-form', function(e) {
    e.preventDefault();
    handleFormAjax($(this), 'editProductModal');
});


    // ---------- VIEW PRODUCT via modal ----------
    $(document).on('click', '.view-btn', function() {
        const url = $(this).data('url');
        $('#view-product-modal-content').html(`<div class="modal-body text-center py-5"><div class="spinner-border text-primary"></div><div class="mt-2">Loading...</div></div>`);
        $('#viewProductModal').modal('show');

        $.get(url)
            .done(function(html) {
                $('#view-product-modal-content').html(html);
            }).fail(function() {
                $('#view-product-modal-content').html('<div class="modal-body p-4">Failed to load product.</div>');
            });
    });

    // ---------- OPTIONAL: Low stock check ----------
    setInterval(function() {
        $.get("{{ route('products.check-low-stock') }}", function(data) {
            if (data.count > 0) {
                toastr.warning(data.count + ' product(s) need restocking!');
            }
        });
    }, 300000); // every 5 mins

});
</script>
<script>

    $(document).on('click', '.view-purchases-btn', function() {
    var productId = $(this).data('product-id');
    var container = $('#purchase-modal-content');

    container.html('<div class="text-center py-5"><div class="spinner-border text-primary"></div><div class="mt-2">Loading purchase history...</div></div>');

    $.get('/products/' + productId + '/purchases')
        .done(function(html) {
            container.html(html);
        })
        .fail(function() {
            container.html('<div class="alert alert-danger">Failed to load purchases.</div>');
        });
});

</script>

</body>
</html>
