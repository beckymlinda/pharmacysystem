<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="container py-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">
            <i class="bi bi-bar-chart-line-fill me-2"></i>Profit / Loss Report
        </h2>
       <form id="filterForm" class="d-flex flex-wrap gap-2">
    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
    <button type="submit" class="btn btn-outline-primary">
        <i class="bi bi-filter-circle me-1"></i> Filter
    </button>
</form>

    </div>

    <!-- Summary Cards -->
    <div class="row g-4">
        <!-- Purchases & Expenses -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100 border-primary">
                <div class="card-body">
                    <h5 class="text-primary mb-3">
                        <i class="bi bi-cart-check me-2"></i>Purchases & Expenses
                    </h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            Starting Capital
                            <span class="fw-bold">MWK {{ number_format($startingCapital, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Total Purchases
                            <span class="fw-bold">MWK {{ number_format($totalPurchase, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Expenses
                            <span class="fw-bold">MWK {{ number_format($totalExpense, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Current Stock Value</strong>
                            <span class="fw-bold ">MWK {{ number_format($totalStockValue, 2) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Sales -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100 border-success">
                <div class="card-body">
                    <h5 class="text-success mb-3">
                        <i class="bi bi-bag-check me-2"></i>Sales
                    </h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            Total Sales
                            <span class="fw-bold text-success">MWK {{ number_format($totalSales, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Sales Returns
                            <span class="fw-bold">MWK {{ number_format($totalReturns, 2) }}</span>
                        </li>
                    </ul>
                    <hr>
                    <h5 class="text-dark mt-3 d-flex justify-content-between">
                        <span><i class="bi bi-cash-stack me-2"></i>Net Profit / Loss</span>
                        <span class="fw-bold text-primary">MWK {{ number_format($profit, 2) }}</span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Filter Results Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reportModalLabel">
          <i class="bi bi-bar-chart-line-fill me-2"></i>Profit / Loss Report
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="reportModalBody">
        <!-- AJAX content will load here -->
        <div class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<style>

    /* ===== PROFIT/LOSS REPORT STYLING ===== */
.container.py-5 {
    max-width: 1200px;
    margin: auto;
}

/* Header */
.container.py-5 h2 {
    font-size: 2rem;
}

/* Filter Form */
.container form input,
.container form button {
    min-width: 140px;
}

/* Cards */
.card {
    border-radius: 12px;
    transition: all 0.3s ease;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

/* Card header icons */
.card h5 i {
    font-size: 1.2rem;
}

/* List group items inside cards */
.card .list-group-item {
    font-size: 1rem;
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: none;
    border-bottom: 1px solid #e5e5e5;
    transition: background 0.2s ease;
}

.card .list-group-item:last-child {
    border-bottom: none;
}

.card .list-group-item:hover {
    background: rgba(0,0,0,0.03);
}

/* Net Profit/Loss text */
.text-success {
    color: #28a745 !important;
}

.text-warning {
    color: #ffc107 !important;
}

.text-primary {
    color: #0d6efd !important;
}

.fw-bold {
    font-weight: 600 !important;
}

/* Responsive adjustments */
@media (max-width: 767px) {
    .container.py-5 .d-flex.justify-content-between {
        flex-direction: column;
        gap: 10px;
    }
    .container form {
        flex-direction: column;
    }
    .container form input,
    .container form button {
        width: 100%;
    }
}

/* Hover shadow effect for cards */
.card.shadow-sm {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

/* Horizontal line */
.card hr {
    border-top: 1px solid #ddd;
}

/* Profit / Loss amount styling */
h5.d-flex.justify-content-between span.fw-bold {
    font-size: 1.1rem;
}

/* Optional: add subtle background gradient to cards */
.card-body {
    background: #fff;
    border-radius: 12px;
}

</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();

        // Get form data
        var formData = $(this).serialize();

        // Show modal
        var reportModal = new bootstrap.Modal(document.getElementById('reportModal'));
        reportModal.show();

        // Load AJAX content
        $.ajax({
            url: "{{ route('reports.profit_loss') }}",
            type: 'GET',
            data: formData,
            success: function(response) {
                // Replace modal body with results
                $('#reportModalBody').html(response);
            },
            error: function(xhr) {
                $('#reportModalBody').html('<div class="text-danger text-center py-4">Error loading report. Please try again.</div>');
            }
        });
    });
});
</script>
