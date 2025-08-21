<div class="container">
    <h3 class="mb-4">All Sales</h3>

    <!-- Filters -->
    <form id="filter-form" class="row g-3 mb-3">
        <div class="col-md-3">
            <label>From Date</label>
            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
        </div>
        <div class="col-md-3">
            <label>To Date</label>
            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
        </div>
        <div class="col-md-3">
            <label>Cashier</label>
            <select name="cashier_id" class="form-select">
                <option value="">All</option>
                @foreach($cashiers as $cashier)
                    <option value="{{ $cashier->id }}" {{ request('cashier_id') == $cashier->id ? 'selected' : '' }}>
                        {{ $cashier->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label>Payment Method</label>
            <select name="payment_method" class="form-select">
                <option value="">All</option>
                <option value="Cash" {{ request('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                <option value="Advance" {{ request('payment_method') == 'Advance' ? 'selected' : '' }}>Advance</option>
                <option value="Standard Bank" {{ request('payment_method') == 'Standard Bank' ? 'selected' : '' }}>Standard Bank</option>
                <option value="National Bank" {{ request('payment_method') == 'National Bank' ? 'selected' : '' }}>National Bank</option>
                <option value="Airtel Money" {{ request('payment_method') == 'Airtel Money' ? 'selected' : '' }}>Airtel Money</option>
                <option value="Mpamba" {{ request('payment_method') == 'Mpamba' ? 'selected' : '' }}>Mpamba</option>
            </select>
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <div class="mb-3">
        <button class="btn btn-success" onclick="window.print()">Print Results</button>
    </div>

    <!-- Filtered sales table will load here -->
    <div id="sales-results">
        @include('Pos.partials.sales_table', ['sales' => $sales])
    </div>
</div>

<script>
$(document).ready(function() {
    $('#filter-form').submit(function(e) {
        e.preventDefault(); // Stop form from refreshing the page

        let url = "{{ route('pos.saleslist') }}"; // Route to your sales list controller
        let data = $(this).serialize();

        $('#sales-results').html('<p class="text-muted">Loading...</p>'); // Show loading text

        $.ajax({
            url: url,
            type: 'GET',
            data: data,
            success: function(response) {
                $('#sales-results').html(response); // Replace sales table
            },
            error: function() {
                $('#sales-results').html('<p class="text-danger">Failed to load filtered sales.</p>');
            }
        });
    });
});
</script> 