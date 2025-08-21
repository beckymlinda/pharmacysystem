<h4 class="mb-4">
    <i class="bi bi-pencil-square text-primary"></i> Edit Product
</h4>

<form id="edit-product-form" action="{{ route('products.update', $product->id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- Product Name --}}
    <div class="mb-3">
        <label for="name" class="form-label fw-semibold">Product Name</label>
        <input type="text" class="form-control" id="name" name="name"
               value="{{ old('name', $product->name) }}" required>
        <div class="invalid-feedback" id="error-name"></div>
    </div>

    {{-- Category --}}
    <div class="mb-3">
        <label for="category" class="form-label fw-semibold">Category</label>
        <input type="text" class="form-control" id="category" name="category"
               value="{{ old('category', $product->category) }}">
        <div class="invalid-feedback" id="error-category"></div>
    </div>

    {{-- Brand --}}
    <div class="mb-3">
        <label for="brand" class="form-label fw-semibold">Brand</label>
        <input type="text" class="form-control" id="brand" name="brand"
               value="{{ old('brand', $product->brand) }}">
        <div class="invalid-feedback" id="error-brand"></div>
    </div>

    {{-- Seller --}}
    <div class="mb-3">
        <label for="seller" class="form-label fw-semibold">Seller</label>
        <input type="text" class="form-control" id="seller" name="seller"
               value="{{ old('seller', $product->seller) }}">
        <div class="invalid-feedback" id="error-seller"></div>
    </div>
 

    {{-- Alert Quantity --}}
    <div class="mb-3">
        <label for="alert_quantity" class="form-label fw-semibold">Alert Quantity</label>
        <input type="number" class="form-control" id="alert_quantity" name="alert_quantity"
               value="{{ old('alert_quantity', $product->alert_quantity) }}" min="0">
        <div class="invalid-feedback" id="error-alert_quantity"></div>
    </div>

    {{-- Order Price --}}
    <div class="mb-3">
        <label for="order_price" class="form-label fw-semibold">Order Price (MK)</label>
        <input type="number" class="form-control" id="order_price" name="order_price"
               value="{{ old('order_price', $product->order_price) }}" step="0.01" min="0">
        <div class="invalid-feedback" id="error-order_price"></div>
    </div>

    {{-- Selling Price --}}
    <div class="mb-3">
        <label for="selling_price" class="form-label fw-semibold">Selling Price (MK)</label>
        <input type="number" class="form-control" id="selling_price" name="selling_price"
               value="{{ old('selling_price', $product->selling_price) }}" step="0.01" min="0">
        <div class="invalid-feedback" id="error-selling_price"></div>
    </div>

    {{-- Expiry Date --}}
    <div class="mb-3">
        <label for="expiry_date" class="form-label fw-semibold">Expiry Date</label>
        <input type="date" class="form-control" id="expiry_date" name="expiry_date"
               value="{{ old('expiry_date', $product->expiry_date?->format('Y-m-d')) }}">
        <div class="invalid-feedback" id="error-expiry_date"></div>
    </div>

    {{-- Purchase Frequency (readonly) --}}
    <div class="mb-3">
        <label for="purchase_frequency" class="form-label fw-semibold">Purchase Frequency</label>
        <input type="number" class="form-control" id="purchase_frequency" name="purchase_frequency"
               value="{{ old('purchase_frequency', $product->purchase_frequency) }}" readonly>
        <small class="text-muted">Automatically updated by system</small>
    </div>

    {{-- Submit Button --}}
    <button type="submit" class="btn btn-primary w-100" id="update-btn">
        <span id="btn-text"><i class="bi bi-save"></i> Update Product</span>
        <span id="btn-loading" class="d-none">
            <i class="bi bi-arrow-repeat spin"></i> Updating...
        </span>
    </button>
</form>

<script>
    $(document).ready(function () {
        $('#edit-product-form').on('submit', function (e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');
            let formData = form.serialize();

            // Reset error messages
            $('.invalid-feedback').text('');
            $('.form-control').removeClass('is-invalid');

            // Show loading state
            $('#btn-text').addClass('d-none');
            $('#btn-loading').removeClass('d-none');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function (response) {
                    $('#main-content').html(`
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle"></i> Product updated successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);

                    // Reload product list after short delay
                    setTimeout(() => {
                        $('.ajax-link[data-url="{{ route('products.index') }}"]').click();
                    }, 1500);
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            $(`#${key}`).addClass('is-invalid');
                            $(`#error-${key}`).text(value[0]);
                        });
                    } else {
                        $('#main-content').prepend(`
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="bi bi-exclamation-triangle"></i> Something went wrong.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                    }
                },
                complete: function () {
                    $('#btn-loading').addClass('d-none');
                    $('#btn-text').removeClass('d-none');
                }
            });
        });
    });
</script>

<style>
    .spin {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        100% { transform: rotate(360deg); }
    }
</style>
