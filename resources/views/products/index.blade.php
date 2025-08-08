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
<div class="container mt-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Manage Products</h4>

        <div class="d-flex gap-2">
            <a href="{{ route('products.export') }}" class="btn btn-outline-primary" id="export-btn">
                <i class="bi bi-download"></i> Export CSV
            </a>

            <!-- button to show add product form modal -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="bi bi-plus-circle"></i> Add Product
            </button>
        </div>
    </div>

    <!-- Success / Error placeholder -->
    <div id="product-messages"></div>

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
                            <th>Expiry</th>
                            <th>Seller</th>
                            <th>Purchases</th>
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
                        @endphp
                        <tr data-id="{{ $product->id }}">
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->brand ?? '-' }}</td>
                            <td>{{ $product->category ?? '-' }}</td>
                            <td class="{{ $isLow ? 'low-stock' : '' }}">{{ $product->quantity }}</td>
                            <td>{{ $product->alert_quantity ?? 0 }}</td>
                            <td>{{ number_format($product->order_price ?? 0, 2) }}</td>
                            <td>{{ number_format($product->selling_price ?? 0, 2) }}</td>
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
                            <td>{{ $product->purchase_frequency ?? 0 }}</td>

                            <td class="text-end">
                                <div class="btn-group" role="group" aria-label="actions">
                                    <button
                                        class="btn btn-sm btn-success adjust-stock-btn"
                                        data-url="{{ route('products.adjust.form', $product->id) }}"
                                        data-id="{{ $product->id }}"
                                    >
                                        <i class="bi bi-arrow-down-up"></i> Adjust
                                    </button>

                                    <button
                                        class="btn btn-sm btn-warning edit-btn"
                                        data-url="{{ route('products.edit', $product->id) }}"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <button
                                        class="btn btn-sm btn-info view-btn"
                                        data-url="{{ route('products.show', $product->id) }}"
                                    >
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

{{-- ---------------------------
     EDIT PRODUCT MODAL (AJAX-loaded)
   --------------------------- --}}
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" id="edit-product-modal-content">
      {{-- content injected by AJAX (route: products.edit returns a partial view) --}}
      <div class="modal-body text-center py-5">
        <div class="spinner-border text-primary" role="status"></div>
        <div class="mt-2">Loading...</div>
      </div>
    </div>
  </div>
</div>

{{-- ---------------------------
     ADJUST STOCK MODAL
   --------------------------- --}}
<div class="modal fade" id="adjustStockModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <form id="adjust-stock-form" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Adjust Stock</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="product_id" id="adjust-product-id">
          <div class="mb-2">
            <label class="form-label">Current Quantity</label>
            <input id="adjust-current-qty" class="form-control" readonly>
          </div>

          <div class="mb-2">
            <label class="form-label">Incoming Quantity (to ADD)</label>
            <input id="adjust-added-qty" name="added_stock" type="number" min="1" class="form-control" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Note (optional)</label>
            <input name="note" class="form-control">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Adjust</button>
        </div>
      </form>
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
                // reload products partial (simple approach: refresh page area)
                reloadProductsTable();
                $('#addProductModal').modal('hide');
                $form[0].reset();
                showMessage('success', 'Product added successfully.');
            })
            .fail(function(xhr) {
                const err = xhr.responseJSON?.message || xhr.responseText || 'Failed to add product';
                showMessage('danger', err, 7000);
            });
    });

    // ---------- RELOAD PRODUCTS TABLE ----------
    function reloadProductsTable() {
        // Assumes route('products.index') returns the same whole page partial for products table.
        // You may create a dedicated route that returns just the table rows to be more efficient.
        $.get("{{ route('products.index') }}", { _partial: 1 })
            .done(function(html) {
                // try to replace tbody with server returned tbody (server should detect _partial and return only tbody)
                // fallback: replace entire page content
                const newTbody = $(html).find('#products-tbody').html();
                if(newTbody) {
                    $('#products-tbody').html(newTbody);
                } else {
                    // last resort: reload full page
                    location.reload();
                }
            })
            .fail(function() { location.reload(); });
    }

    // ---------- DELETE PRODUCT via AJAX ----------
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        if(!confirm('Are you sure you want to delete this product? This action cannot be undone.')) return;

        const $form = $(this);
        const url = $form.attr('action');

        $.ajax({
            url: url,
            method: 'POST',
            data: $form.serialize(), // contains _method=DELETE
        }).done(function(res) {
            showMessage('success', res.message || 'Product deleted.');
            // remove row
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
                // server should return a small blade partial with the edit form
                $('#edit-product-modal-content').html(html);
            }).fail(function() {
                $('#edit-product-modal-content').html('<div class="modal-body p-4">Failed to load form.</div>');
            });
    });

    // If edit form is submitted (form inside modal), handle it via delegation
    $(document).on('submit', '#edit-product-form', function(e) {
        e.preventDefault();
        const $form = $(this);
        const url = $form.attr('action');
        const method = $form.find('input[name=_method]').val() || 'POST';
        const data = $form.serialize();

        $.ajax({
            url: url,
            method: method,
            data: data
        }).done(function(res) {
            showMessage('success', res.message || 'Product updated.');
            $('#editProductModal').modal('hide');
            reloadProductsTable();
        }).fail(function(xhr) {
            const err = xhr.responseJSON?.message || 'Update failed';
            showMessage('danger', err, 7000);
        });
    });

    // ---------- VIEW: load view content in modal ----------
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

    // ---------- ADJUST STOCK: open modal and submit ----------
    $(document).on('click', '.adjust-stock-btn', function() {
        const pid = $(this).data('id');
        const row = $(`tr[data-id="${pid}"]`);
        const currentQty = row.find('td').eq(3).text().trim(); // qty column index
        $('#adjust-product-id').val(pid);
        $('#adjust-current-qty').val(currentQty);
        $('#adjust-added-qty').val('');
        $('#adjustStockModal').modal('show');
    });

    $('#adjust-stock-form').on('submit', function(e) {
        e.preventDefault();
        const pid = $('#adjust-product-id').val();
        const url = "{{ url('products') }}/" + pid + "/adjust"; // route: products.adjust (POST)
        const data = $(this).serialize();

        $.post(url, data)
            .done(function(res) {
                showMessage('success', res.message || 'Stock adjusted.');
                $('#adjustStockModal').modal('hide');
                reloadProductsTable();
            })
            .fail(function(xhr) {
                showMessage('danger', 'Adjust failed: ' + (xhr.responseJSON?.message || xhr.responseText));
            });
    });

    // ---------- Export button behavior (if you want AJAX or direct link)
    $('#export-btn').on('click', function() {
        // If you want additional params (filters) to be sent, build URL with query string.
        // Default behavior: follow the link and download the CSV.
    });

    // Optional: auto-refresh table every X minutes
    // setInterval(reloadProductsTable, 1000 * 60 * 5);
});
</script>
</body>
</html>
