<div class="container">
    <h3 class="mb-4">Sales Report</h3>

    <!-- Filter Form -->
    <form id="filterForm" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Payment Method</label>
            <select name="payment_method" class="form-control">
                <option value="">-- All --</option>
                <option value="cash">Cash</option>
                <option value="card">Card</option>
                <option value="mobile_money">Mobile Money</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- Sales Table -->
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Sale ID</th>
                <th>Date</th>
                <th>User</th>
                <th>Products</th>
                <th>Payment Methods</th>
                <th>Total Sale Amount</th>
            </tr>
        </thead>
        <tbody id="salesBody">
            <tr><td colspan="6" class="text-center">Loading...</td></tr>
        </tbody>
    </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("filterForm");
    const salesBody = document.getElementById("salesBody");

    async function fetchSales() {
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();

        const res = await fetch("{{ route('sales.fetch') }}?" + params);
        const data = await res.json();

        salesBody.innerHTML = "";

        if (data.sales && data.sales.length > 0) {
            data.sales.forEach(sale => {
                // Build product list HTML
                let productsHtml = '';
                sale.products.forEach(p => {
                    productsHtml += `<div>${p.name} - Qty: ${p.quantity} - Price: ${Number(p.price).toFixed(2)} - Total: ${Number(p.total).toFixed(2)}</div>`;
                });

                // Payment methods
                let paymentHtml = sale.payment_methods.join(', ');

                salesBody.innerHTML += `
                    <tr>
                        <td>${sale.id}</td>
                        <td>${sale.sale_date}</td>
                        <td>${sale.user}</td>
                        <td>${productsHtml}</td>
                        <td>${paymentHtml}</td>
                        <td>${Number(sale.total_amount).toFixed(2)}</td>
                    </tr>
                `;
            });
        } else {
            salesBody.innerHTML = `<tr><td colspan="6" class="text-center">No sales found.</td></tr>`;
        }
    }

    // Fetch when form is submitted
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        fetchSales();
    });

    // Fetch all sales on page load
    fetchSales();
});
</script>
