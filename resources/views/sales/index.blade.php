{{-- Include jsPDF CDN --}}
<div class="container mt-4">

    {{-- Export + Title --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-primary">📊 Sales Report</h3>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-success btn-sm" id="exportCSV">
                <i class="bi bi-file-earmark-spreadsheet"></i> CSV
            </button>
            <button class="btn btn-outline-danger btn-sm" id="exportPDF">
                <i class="bi bi-filetype-pdf"></i> PDF
            </button>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white fw-bold">
            🔎 Filter Sales
        </div>
        <div class="card-body">
            <form id="filterForm" class="row g-3">
   
<div class="col-md-3">
    <label class="form-label">Date Range</label>
    <select name="date_range" id="dateRange" class="form-select">
        <option value="">-- Select Range --</option>
        <option value="today">Today</option>
        <option value="yesterday">Yesterday</option>
        <option value="last_7_days">Last 7 Days</option>
        <option value="last_30_days">Last 30 Days</option>
        <option value="this_month">This Month</option>
        <option value="last_month">Last Month</option>
        <option value="this_month_last_year">This Month Last Year</option>
        <option value="this_year">This Year</option>
        <option value="last_year">Last Year</option>
        <option value="current_financial_year">Current Financial Year</option>
        <option value="last_financial_year">Last Financial Year</option>
    </select>
</div>

{{-- Always visible custom range --}}
<div class="col-md-3">
    <label class="form-label">Custom Range (Start)</label>
    <input type="date" name="start_date" class="form-control">
</div>
<div class="col-md-3">
    <label class="form-label">Custom Range (End)</label>
    <input type="date" name="end_date" class="form-control">
</div>


                <div class="col-md-3">
                    <label class="form-label">Seller</label>
                    <select name="seller_id" class="form-select">
                        <option value="">All Sellers</option>
                        @foreach($sellers as $seller)
                            <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select">
                        <option value="">All Methods</option>
                        <option value="Cash">Cash</option>
                        <option value="Advance">Advance</option>
                        <option value="Standard Bank">Standard Bank</option>
                        <option value="National Bank">National Bank</option>
                        <option value="Airtel Money">Airtel Money</option>
                        <option value="Mpamba">Mpamba</option>
                    </select>
                </div>

                {{-- Buttons --}}
               <div class="col-md-2 d-flex align-items-end">
    <button type="submit" class="btn w-100" style="background-color:#78b043; color:#fff; border:none;">
        <i class="bi bi-filter"></i> Apply
    </button>
</div>
<div class="col-md-2 d-flex align-items-end">
    <button type="button" id="clearFilter" class="btn btn-outline-secondary w-100">
        <i class="bi bi-x-circle"></i> Clear
    </button>
</div>

            </form>
        </div>
    </div>

    {{-- Sales Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white fw-bold">
            📑 Sales Records
        </div>
        <div class="card-body p-0">
            <div id="salesTableContainer" class="table-responsive">
                @include('sales._table', ['sales' => $sales])
            </div>
        </div>
    </div>
</div>

</div> 

<style>

    .card {
    border-radius: 12px;
}

.card-header {
    font-size: 1rem;
    letter-spacing: 0.5px;
}

table {
    margin-bottom: 0;
}

.btn {
    border-radius: 8px;
}

/* Theme colors */
.text-primary {
    color: #1a73e8 !important; /* Blue */
}

.bg-primary {
    background-color: #1977bd !important;
}

.bg-success {
    background-color: #9acb4c !important; /* Apple green */
}

</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

<script>
$(document).ready(function () {

    // ✅ CSV Export (safe binding)
    $(document).off('click', '#exportCSV').on('click', '#exportCSV', function () {
        console.log("CSV export clicked");

        const table = $('#salesTableContainerInner tbody tr');
        if (!table.length) {
            alert("No rows found to export!");
            return;
        }

        let csvContent = "data:text/csv;charset=utf-8,Sale ID,Product,Unit Price,Quantity,Total,Sale Date,Seller,Payment Methods\n";

        table.each(function () {
            let row = $(this).find('td').map(function () {
                return `"${$(this).text().trim().replace(/"/g, '""')}"`;
            }).get();
            csvContent += row.join(",") + "\n";
        });

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "sales_report.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    // ✅ PDF Export (safe binding)
    $(document).off('click', '#exportPDF').on('click', '#exportPDF', function () {
        console.log("PDF export clicked");

        const { jsPDF } = window.jspdf;
        if (!jsPDF) {
            alert("jsPDF not loaded!");
            return;
        }

        const table = document.getElementById("salesTableContainerInner");
        if (!table) {
            alert("No table found to export!");
            return;
        }

        const doc = new jsPDF();
        doc.setFontSize(16);
        doc.text("EDUC PHARMACY Sales Report", 14, 15);

        // AutoTable export
        doc.autoTable({
            html: '#salesTableContainerInner',
            startY: 25,
            styles: { fontSize: 8 }
        });

        doc.save("sales_report.pdf");
    });

});


// ✅ Clear Filter
$(document).off('click', '#clearFilter').on('click', '#clearFilter', function () {
    const form = document.getElementById('filterForm');
    form.reset(); // clears all inputs/selects

    // Reload table with default (no filters)
    fetch("{{ route('sales.fetch') }}")
        .then(res => res.text())
        .then(html => {
            document.querySelector('#salesTableContainer').innerHTML = html;
        });
});

</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const dateRange = document.getElementById('dateRange');
    const customFields = document.querySelectorAll('.custom-range');

    function toggleCustomFields() {
        if (dateRange.value === 'custom') {
            customFields.forEach(el => el.classList.remove('d-none'));
        } else {
            customFields.forEach(el => el.classList.add('d-none'));
        }
    }

    // Initial check (in case "custom" was preselected)
    toggleCustomFields();

    // Change event
    dateRange.addEventListener('change', toggleCustomFields);
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const dateRange = document.getElementById('dateRange');
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');

    function toggleDateInputs() {
        if (dateRange.value === 'custom') {
            startDate.disabled = false;
            endDate.disabled = false;
        } else {
            startDate.disabled = true;
            endDate.disabled = true;
            startDate.value = "";
            endDate.value = "";
        }
    }

    // Run on load + on change
    toggleDateInputs();
    dateRange.addEventListener('change', toggleDateInputs);
});
</script>
