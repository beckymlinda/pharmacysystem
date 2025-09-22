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
  <!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
<div class="row mb-3">
    <div class="col-3">
        <div class="input-group">
            <span class="input-group-text" id="search-icon">
                <i class="bi bi-search"></i>
            </span>
            <input type="search" 
                   id="product-search" 
                   placeholder="Search product..." 
                   class="form-control" 
                   aria-label="Search" 
                   aria-describedby="search-icon">
        </div>
    </div>
</div>
            <table class="table table-striped table-hover mb-0 align-middle" id="products-table">
           <thead class="table-light">
<tr>
    <th>Name</th>
    <th>Brand</th>
    <th>Category</th>
    <th>Qty</th>
    <th>Unit</th>
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

{{-- Quantity --}}
<td class="{{ $isLow ? 'low-stock' : '' }}">{{ $product->quantity }}</td>

{{-- Unit --}}
<td>{{ $product->unit->short_name ?? '-' }}</td>

{{-- Alert Quantity --}}
<td>{{ $product->alert_quantity ?? 0 }}</td>

{{-- Prices --}}
<td>{{ number_format($orderPrice, 2) }}</td>
<td>{{ number_format($sellingPrice, 2) }}</td>

{{-- Profit with "View" link --}}<td>
   {{ number_format($profit, 2) }}
<a href="#"
   class="ms-2 text-decoration-underline small view-profit"
   data-bs-toggle="modal"
   data-bs-target="#profitModal"
   data-product-name="{{ $product->name }}"
   data-order-price="{{ number_format($orderPrice,2) }}"
   data-profit="{{ number_format($profit,2) }}"
   data-margin="{{ number_format($margin,2) }}"
   data-exctax="{{ number_format($sellingPrice,2) }}"
   data-inctax="{{ number_format($sellingPrice,2) }}"  {{-- include tax if needed --}}
   data-tax="{{ number_format($sellingPrice - $orderPrice,2) }}" {{-- adjust if you have tax --}}
   data-current-stock="{{ $product->quantity }}"
   data-stock-value="{{ number_format($product->quantity * $orderPrice,2) }}"
   data-units-sold="{{ $product->total_units_sold ?? 0 }}"
   data-purchase-frequency="{{ $product->purchase_frequency ?? 0 }}"
   data-current-cash="{{ number_format($product->current_cash ?? 0, 2) }}">
   View
</a>

</td>
{{-- Expiry --}}
<td>
    @if(empty($product->expiry_date) || $product->expiry_date === '0000-00-00' || $product->expiry_date === '0000-00-00 00:00:00')
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


{{-- Seller --}}
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

<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="add-product-form" action="{{ route('products.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add Product</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <!-- Product Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" name="category" id="category" class="form-control">
                    </div>

                    <!-- Unit Search -->
                    <div class="mb-3 position-relative">
                        <label class="form-label">Search Unit</label>
                        <input type="search" id="unit-search" class="form-control" placeholder="Type unit name...">
                        <input type="hidden" id="unit_id" name="unit_id">
                        <ul id="unit-list" class="list-group"></ul>
                    </div>

                    <!-- Quantity -->
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" required>
                    </div>

                    <!-- Alert Quantity -->
                    <div class="mb-3">
                        <label for="alert_quantity" class="form-label">Alert Quantity</label>
                        <input type="number" name="alert_quantity" id="alert_quantity" class="form-control" required>
                    </div>

                    <!-- Prices -->
                    <div class="mb-3">
                        <label for="order_price" class="form-label">Order Price</label>
                        <input type="number" name="order_price" id="order_price" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="selling_price" class="form-label">Selling Price</label>
                        <input type="number" name="selling_price" id="selling_price" class="form-control">
                    </div>

                    <!-- Expiry Date -->
                    <div class="mb-3">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="date" name="expiry_date" id="expiry_date" class="form-control" required>
                    </div>

                    <!-- Brand -->
                    <div class="mb-3">
                        <label for="brand" class="form-label">Brand</label>
                        <input type="text" name="brand" id="brand" class="form-control">
                    </div>

                    <!-- Seller -->
                    <div class="mb-3">
                        <label for="seller" class="form-label">Seller</label>
                        <input type="text" name="seller" id="seller" class="form-control">
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (optional)</label>
                        <textarea name="notes" id="notes" class="form-control"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- CSS fix for dropdown inside modal -->
<style>
#unit-list {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    z-index: 1055; /* above modal backdrop */
    max-height: 200px;
    overflow-y: auto;
}
</style>

<!-- JS: Unit Search Dropdown -->
 

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
<!-- PROFIT DETAILS MODAL -->
<div class="modal fade" id="profitModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      
      <!-- Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="p-product-name">Product Name</h5> <!-- Product name set dynamically -->
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <div class="row g-3">
          <!-- Profit & Margin -->
          <div class="col-md-6">
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><strong>Order Price:</strong> <span id="p-order-price"></span></li>
              <li class="list-group-item"><strong>Profit:</strong> <span id="p-profit"></span></li>
              <li class="list-group-item"><strong>Margin %:</strong> <span id="p-margin"></span></li>
              <li class="list-group-item"><strong>Selling Price (Ex. Tax):</strong> <span id="p-exctax"></span></li>
              <li class="list-group-item"><strong>Selling Price (Inc. Tax):</strong> <span id="p-inctax"></span></li>
              <li class="list-group-item"><strong>Tax Amount:</strong> <span id="p-tax"></span></li>
            </ul>
          </div>

          <!-- Stock Details -->
          <div class="col-md-6">
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><strong>Current Stock:</strong> <span id="p-current-stock"></span></li>
              <li class="list-group-item"><strong>Stock Value Total:</strong> <span id="p-stock-value"></span></li>
              <li class="list-group-item"><strong>Total Units Sold:</strong> <span id="p-units-sold"></span></li>
              <li class="list-group-item"><strong>Purchase Frequency:</strong> <span id="p-purchase-frequency"></span></li>
              <li class="list-group-item">    <strong>Current Cash:</strong> <span id="p-current-cash"></span></li>

            </ul>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>


<!--purchae modal-->
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
   <!-- jQuery (must be loaded first) -->
 
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
 
<!-- Moment.js for date handling -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

  <script>
    $(document).ready(function(){
    const $input = $('#unit-search');
    const $list = $('#unit-list');

    $input.on('input', function() {
        let query = $input.val().trim();
        $list.empty();

        if(!query) return;

        $.get("{{ route('units.fetch') }}", { q: query })
         .done(function(data){
            if(!data.length){
                $list.html('<li class="list-group-item">No results found</li>');
                return;
            }
            data.forEach(unit => {
                let $li = $('<li class="list-group-item list-group-item-action"></li>');
                $li.text(`${unit.name} (${unit.short_name})`).css('cursor','pointer');
                $li.on('click', function(){
                    $input.val(unit.name);
                    $('#unit_id').val(unit.id);
                    $list.empty();
                });
                $list.append($li);
            });
         })
         .fail(function(err){ console.log(err); });
    });

    // click outside closes dropdown
    $(document).on('click', function(e){
        if(!$(e.target).closest('#unit-search, #unit-list').length){
            $list.empty();
        }
    });
});

  </script>

  
<script>
$(document).ready(function() {

    // ---------- CSRF setup for AJAX ----------
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // ---------- Initialize Select2 ----------
    

    // ---------- Utility: show messages ----------
    function showMessage(type, text, timeout = 4000) {
        const html = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${text}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                      </div>`;
        $('#product-messages').html(html);
        if(timeout) setTimeout(() => $('.alert').alert('close'), timeout);
    }

    // ---------- ADD / EDIT PRODUCT via AJAX ----------
    
     // ===============================
// Generic AJAX form handler
// ===============================
 function handleFormAjax(form, modalId = null) {
    let formData = form.serialize();

    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: formData,
        success: function(response) {
            if (response.success) {
                // ✅ Hide modal if provided
                if (modalId) {
                    $('#' + modalId).modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open').css('padding-right', '');
                }

                // ✅ Show success message
                if (typeof toastr !== "undefined") {
                    toastr.success(response.message);
                } else {
                    $('#main-content').prepend(`
                        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                            <i class="bi bi-check-circle"></i> ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                }

                // ✅ Reload product list cleanly
                setTimeout(() => {
                    $.ajax({
                        url: "{{ route('products.index') }}",
                        type: "GET",
                        success: function (data) {
                            $('#main-content').html(data);
                        }
                    });
                }, 1000);
            } else {
                alert('Something went wrong');
            }
        },
        error: function(xhr) {
            let msg = xhr.responseJSON?.message ?? 'Unknown error';
            if (typeof toastr !== "undefined") {
                toastr.error(msg);
            } else {
                alert('Error: ' + msg);
            }
        }
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

    // ---------- DELETE PRODUCT via AJAX ----------
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        if(!confirm('Are you sure?')) return;
        const $form = $(this);
        $.post($form.attr('action'), $form.serialize())
            .done(res => {
                showMessage('success', res.message || 'Deleted.');
                $form.closest('tr').remove();
            })
            .fail(xhr => showMessage('danger', 'Delete failed: ' + (xhr.responseJSON?.message || xhr.responseText)));
    });

    // ---------- VIEW PRODUCT ----------
    $(document).on('click', '.view-btn', function() {
        const url = $(this).data('url');
        $('#view-product-modal-content').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div><div class="mt-2">Loading...</div></div>');
        $('#viewProductModal').modal('show');
        $.get(url).done(html => $('#view-product-modal-content').html(html))
                  .fail(() => $('#view-product-modal-content').html('<div class="modal-body p-4">Failed to load product.</div>'));
    });

    // ---------- VIEW PROFIT ----------
   // PROFIT MODAL UPDATE
$(document).on('click', '.view-profit', function() {
    // Existing fields
    $('#p-profit').text($(this).data('profit'));
    $('#p-margin').text($(this).data('margin') + '%');
    $('#p-exctax').text($(this).data('exctax'));
    $('#p-inctax').text($(this).data('inctax'));
    $('#p-tax').text($(this).data('tax'));

    // New fields
    $('#p-units-sold').text($(this).data('units-sold'));
$('#p-current-cash').text($(this).data('current-cash'));
    $('#p-product-name').text($(this).data('product-name'));
    $('#p-order-price').text($(this).data('order-price'));
    $('#p-current-stock').text($(this).data('current-stock'));
    $('#p-stock-value').text($(this).data('stock-value'));
    $('#p-units-sold').text($(this).data('units-sold'));
    $('#p-purchase-frequency').text($(this).data('purchase-frequency'));
});


    // ---------- VIEW PURCHASE HISTORY ----------
    $(document).on('click', '.view-purchases-btn', function() {
        var productId = $(this).data('product-id');
        var container = $('#purchase-modal-content');
        container.html('<div class="text-center py-5"><div class="spinner-border text-primary"></div><div class="mt-2">Loading purchase history...</div></div>');
        $.get('/products/' + productId + '/purchases')
            .done(html => container.html(html))
            .fail(() => container.html('<div class="alert alert-danger">Failed to load purchases.</div>'));
    });

    // ---------- LOW STOCK CHECK ----------
    function loadLowStock() {
        const list = $('#low-stock-list');
        list.html('<div class="text-center py-5"><div class="spinner-border text-primary"></div><div class="mt-2">Loading...</div></div>');

        $.get("{{ route('products.check-low-stock') }}")
            .done(res => {
                if(res.count === 0) return list.html('<div class="alert alert-success">✅ No products are currently low in stock.</div>');
                let html = `<table class="table table-sm table-bordered"><thead><tr><th>Name</th><th>Qty</th><th>Alert Qty</th></tr></thead><tbody>`;
                res.products.forEach(p => html += `<tr><td>${p.name}</td><td class="text-danger fw-bold">${p.quantity}</td><td>${p.alert_quantity}</td></tr>`);
                html += '</tbody></table>';
                list.html(html);
            }).fail(() => list.html('<div class="alert alert-danger">❌ Failed to load low stock products.</div>'));
    }

    $('#checkLowStockBtn, #low-stock-alert').on('click', function() {
        $('#lowStockModal').modal('show');
        loadLowStock();
    });

    setInterval(() => {
        $.get("{{ route('products.check-low-stock') }}", data => { if(data.count > 0) toastr.warning(data.count + ' product(s) need restocking!'); });
    }, 300000); // every 5 mins

});


// ---------- EDIT: load edit form in modal ----------
$(document).on('click', '.edit-btn', function() {
    const url = $(this).data('url');
    $('#edit-product-modal-content').html(`<div class="modal-body text-center py-5"><div class="spinner-border text-primary"></div><div class="mt-2">Loading...</div></div>`);
    $('#editProductModal').modal('show');

    $.get(url)
        .done(function(html) {
            $('#edit-product-modal-content').html(html);

            // Reinitialize Select2 for unit dropdown inside edit form
            $('#edit-product-form #unit-select').select2({
                dropdownParent: $('#editProductModal'),
                width: '100%',
                placeholder: 'Search or select a unit'
            });
        })
        .fail(function() {
            $('#edit-product-modal-content').html('<div class="modal-body p-4">Failed to load form.</div>');
        });
});

// ---------- SUBMIT EDIT FORM via AJAX using event delegation ----------
$(document).on('submit', '#edit-product-form', function(e) {
    e.preventDefault();
    handleFormAjax($(this), 'editProductModal');
});

</script>
<script>

    $(document).ready(function() {
    const $input = $('#product-search');
    const $tbody = $('#products-tbody');

    $input.on('input', function() {
        const query = $input.val().trim();

        // Show spinner or "loading" message
        $tbody.html('<tr><td colspan="13" class="text-center">Loading...</td></tr>');

        $.get('/products/search', { q: query })
         .done(function(res) {
            $tbody.empty();

            if (!res.products.length) {
                $tbody.html('<tr><td colspan="13" class="text-center text-danger">No products found</td></tr>');
                return;
            }

            res.products.forEach(product => {
                const profit = (product.selling_price ?? 0) - (product.order_price ?? 0);
                const isLow = product.quantity <= product.alert_quantity;

                const expiryBadge = (() => {
                    if (!product.expiry_date) return '';
                    const days = moment(product.expiry_date).diff(moment(), 'days');
                    if (days < 0) return '<span class="badge badge-expired ms-1">Expired</span>';
                    if (days <= 30) return '<span class="badge badge-expiring ms-1">Expiring</span>';
                    return '';
                })();

                $tbody.append(`
                    <tr data-id="${product.id}">
                        <td>${product.name}</td>
                        <td>${product.brand ?? '-'}</td>
                        <td>${product.category ?? '-'}</td>
                        <td class="${isLow ? 'low-stock' : ''}">${product.quantity}</td>
                        <td>${product.unit?.short_name ?? '-'}</td>
                        <td>${product.alert_quantity ?? 0}</td>
                        <td>${parseFloat(product.order_price ?? 0).toFixed(2)}</td>
                        <td>${parseFloat(product.selling_price ?? 0).toFixed(2)}</td>
                        <td>${profit.toFixed(2)} 
    <a href="#" class="ms-2 text-decoration-underline small view-profit" 
       data-bs-toggle="modal" 
       data-bs-target="#profitModal"
       data-profit="${profit.toFixed(2)}" 
       data-margin="${product.order_price > 0 ? ((profit / product.order_price) * 100).toFixed(2) : 0}"
       data-exctax="${parseFloat(product.selling_price ?? 0).toFixed(2)}"
       data-inctax="${parseFloat(product.selling_price ?? 0).toFixed(2)}"
       data-tax="${(parseFloat(product.selling_price ?? 0) - parseFloat(product.order_price ?? 0)).toFixed(2)}"
       data-current-stock="${product.quantity}"
       data-stock-value="${(product.quantity * (product.order_price ?? 0)).toFixed(2)}"
       data-units-sold="${product.total_units_sold ?? 0}"
       data-purchase-frequency="${product.purchase_frequency ?? 0}">
       View
    </a>
</td>

                        <td>${product.expiry_date ?? '-'} ${expiryBadge}</td>
                        <td>${product.seller ?? '-'}</td>
                        <td>
                            <button class="btn btn-sm btn-secondary view-purchases-btn" 
                                    data-product-id="${product.id}" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#purchaseModal">
                                View
                            </button>
                        </td>
                        <td class="text-end">
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-warning edit-btn" data-url="/products/${product.id}/edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-info view-btn" data-url="/products/${product.id}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <form action="/products/${product.id}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger delete-btn">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                `);
            });
         })
         .fail(() => {
            $tbody.html('<tr><td colspan="13" class="text-center text-danger">Search failed</td></tr>');
         });
    });
});

</script>
</body>
</html>