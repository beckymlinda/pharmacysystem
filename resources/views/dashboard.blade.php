<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard</title>

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        body {
            background-color: white;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            background-color: #007bff; /* Bootstrap primary blue */
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            padding: 1.5rem 1rem;
            color: white;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: #0056b3; /* Darker shade of blue */
            color: white;
            height: 56px;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Search input */
        .navbar .search-input {
            width: 300px;
            max-width: 100%;
        }

        /* User greeting and profile */
        .navbar .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 500;
            font-size: 1rem;
            color: white;
        }

        .navbar .user-info img,
        .navbar .user-info .user-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #e9ecef;
            color: #007bff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            font-weight: 700;
        }

        .content-wrapper {
            margin-left: 250px;
            margin-top: 56px; /* height of navbar */
            padding: 2rem;
            min-height: 100vh;
        }

        .card {
            border-radius: 0.5rem;
        }

        /* Sidebar links with icons */
        .sidebar a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0.6rem 0.75rem;
            font-weight: 600;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
            transition: background-color 0.2s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #0056b3;
            text-decoration: none;
            color: #cce5ff;
        }

        .sidebar a i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                flex-direction: row;
                overflow-x: auto;
                white-space: nowrap;
                padding: 0.5rem 1rem;
            }
            .sidebar a {
                margin: 0 0.5rem 0 0;
                padding: 0.5rem 0.75rem;
                border-radius: 0.25rem;
                flex-shrink: 0;
            }
            .navbar {
                left: 0;
                top: auto;
                position: relative;
                justify-content: space-between;
                padding: 0.5rem 1rem;
            }
            .content-wrapper {
                margin-left: 0;
                margin-top: 0;
                padding: 1rem;
            }
            .navbar .search-input {
                width: 100%;
                margin-right: 1rem;
            }
        }
    </style>
</head>
<body>

   <nav class="navbar">
<img src="{{ asset('images/logo.png') }}" 
     alt="EDUC Logo" 
     style="height:40px; width:auto;" 
     class="me-3">
        
    <!-- Search bar -->
    <input type="search" class="form-control search-input" placeholder="Search..." aria-label="Search" />

    <!-- User greeting, profile & logout -->
    <div class="user-info d-flex align-items-center gap-3">
        <span>Hello, {{ Auth::user()->name }}</span>

        {{-- Replace below with user's real profile pic URL if available --}}
        @if(Auth::user()->profile_picture_url)
            <img src="{{ Auth::user()->profile_picture_url }}" alt="User Profile Picture" class="rounded-circle" width="35" height="35" />
        @else
            <div class="user-icon">
                <i class="bi bi-person-fill fs-4 text-white"></i>
            </div>
        @endif

        <!-- Logout button -->
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-light">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</nav>


<aside class="sidebar">
    <a href="#"><i class="bi bi-house"></i> Dashboard</a>

    <a href="#" class="ajax-link" data-url="{{ route('products.index') }}">
        <i class="bi bi-box-seam"></i> Products
    </a>

    <a href="#" class="ajax-link" data-url="{{ route('purchases.index') }}">
        <i class="bi bi-cart-plus"></i> Purchases
    </a>
<!-- POS -->
<a href="#" class="ajax-link" data-url="{{ route('pos.index') }}">
    <i class="bi bi-cash-stack"></i> POS
</a>
<!-- All Sales -->
<a href="#" class="ajax-link" data-url="{{ route('sales.page') }}">
    <i class="bi bi-list-check"></i> All Sales
</a>




    <a href="#" class="ajax-link" data-url="{{ route('units.index') }}">
    <i class="bi bi-plus-circle"></i> Add Unit
</a><a href="#" class="ajax-link d-block mt-1" data-url="{{ route('reports.profit_loss') }}">
    <i class="bi bi-bar-chart-line"></i> Profit / Loss Report
</a>


<a href="#" class="ajax-link d-block mt-1" data-url="{{ route('expenses.index') }}">
    <i class="bi bi-wallet2"></i> List Expenses
</a>

 

<a href="#" class="ajax-link d-block mt-1" data-url="{{ route('products.adjust-stock') }}">
    <i class="bi bi-exclamation-triangle"></i> Stock Adjustment
</a>
    <a href="#"><i class="bi bi-bell"></i> Notifications</a>
    <a href="#"><i class="bi bi-gear"></i> Settings</a>
    <a href="#"><i class="bi bi-people"></i> User Management</a>

    <hr class="text-white">

    <a href="#"><i class="bi bi-clipboard-data"></i> Audit Trail</a>

    <a href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</aside>







   <main class="content-wrapper" id="main-content">
       <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Sales Overview</h2>
    <div>
        <button class="btn btn-outline-secondary btn-sm me-2">
            <i class="bi bi-file-earmark-spreadsheet"></i> CSV
        </button>
        <button class="btn btn-outline-danger btn-sm">
            <i class="bi bi-filetype-pdf"></i> PDF
        </button>
    </div>
</div>

<div class="mb-3">
    <label for="dateFilter" class="form-label fw-semibold">Filter by Date:</label>
    <select class="form-select w-auto d-inline-block" id="dateFilter">
        <option value="today">Today</option>
        <option value="this_week" selected>This Week</option>
        <option value="this_month">This Month</option>
    </select>
</div>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title">Total Sales</h6>
                    
                    <h4>MK 820,000 <span class="badge bg-success ms-2">+10%</span></h4>

                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title">Paid Orders</h6>
                    <h4>340</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title">Pending Orders</h6>
                    <h4>28</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title">Profit</h6>
                    <h4>MK 310,000</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title">Sales This Week</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Monday</span><strong>MK 45,000</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Tuesday</span><strong>MK 51,000</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Wednesday</span><strong>MK 72,000</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Thursday</span><strong>MK 69,000</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Friday</span><strong>MK 88,000</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Saturday</span><strong>MK 50,000</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Sunday</span><strong>MK 65,000</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between fw-bold bg-light">
    <span>Total</span><strong>MK 440,000</strong>
</li>

                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title">Sales Progress</h6>
                    <canvas id="salesChart" height="180"></canvas>
                </div>
            </div>
        </div>
    </div>
</main>


    <!-- Bootstrap JS Bundle CDN (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- ✅ Use this older UMD version of jsPDF (browser-friendly) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<!-- jQuery (CDN) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).on('click', '.ajax-link', function(e) {
    e.preventDefault();

    let url = $(this).data('url');

    $('#main-content').html('<div class="text-center p-5">Loading...</div>');

    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            $('#main-content').html(response);
        },
        error: function(xhr) {
            $('#main-content').html('<div class="alert alert-danger">Failed to load content.</div>');
        }
    });
});


</script>

<script>
    function loadContent(url) {
        $.get(url, function (response) {
            $('#main-content').html(response);
        }).fail(function (xhr) {
            alert("Failed to load content: " + xhr.statusText);
        });
    }
</script>

<script>
    const pdfButton = document.querySelector(".btn-outline-danger");

    pdfButton.addEventListener("click", function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        doc.setFontSize(16);
        doc.text("Sales Report - This Week", 10, 15);

        const salesData = [
            { day: "Monday", amount: 45000 },
            { day: "Tuesday", amount: 51000 },
            { day: "Wednesday", amount: 72000 },
            { day: "Thursday", amount: 69000 },
            { day: "Friday", amount: 88000 },
            { day: "Saturday", amount: 50000 },
            { day: "Sunday", amount: 65000 },
        ];

        let y = 30;
        salesData.forEach((row, index) => {
            doc.setFontSize(12);
            doc.text(`${row.day}: MK ${row.amount.toLocaleString()}`, 10, y);
            y += 10;
        });

        doc.save("sales_report.pdf");
    });
</script>


 <script>
    const salesData = [
        { day: "Monday", amount: 45000 },
        { day: "Tuesday", amount: 51000 },
        { day: "Wednesday", amount: 72000 },
        { day: "Thursday", amount: 69000 },
        { day: "Friday", amount: 88000 },
        { day: "Saturday", amount: 50000 },
        { day: "Sunday", amount: 65000 },
    ];

    document.querySelector(".btn-outline-secondary").addEventListener("click", function () {
        let csvContent = "data:text/csv;charset=utf-8,Day,Amount\n";
        salesData.forEach(row => {
            csvContent += `${row.day},${row.amount}\n`;
        });

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "sales_report.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    document.querySelector(".btn-outline-danger").addEventListener("click", function () {
        const doc = new jsPDF();
        doc.text("Sales Report - This Week", 10, 10);
        salesData.forEach((row, index) => {
            doc.text(`${row.day}: MK ${row.amount.toLocaleString()}`, 10, 20 + index * 10);
        });
        doc.save("sales_report.pdf");
    });
</script>

<!-- jsPDF CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


<!-- jsPDF CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
$(document).on('submit', '#edit-purchase-form', function(e) {
    e.preventDefault(); // Prevent normal form submit

    let form = $(this);
    let url = form.attr('action');
    let data = form.serialize();

    $.ajax({
        url: url,
        method: 'POST',
        data: data,
        success: function(response) {
            // Reload purchases list after successful update
            $.get("{{ route('purchases.index') }}", function(data) {
                $('#main-content').html(data);

                // Show a success message inside #main-content (optional)
                $('<div class="alert alert-success mt-2">Purchase updated successfully!</div>')
                    .prependTo('#main-content')
                    .delay(3000)
                    .fadeOut();
            });
        },
        error: function(xhr) {
            alert('Failed to update purchase. Please check your inputs.');
        }
    });
});
</script>
<script>
$(document).on('click', '.ajax-link', function(e) {
    e.preventDefault();
    let url = $(this).data('url');

    $('#main-content').html('<div class="text-center p-5">Loading...</div>');

    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            $('#main-content').html(response);
        },
        error: function(xhr) {
            $('#main-content').html('<div class="alert alert-danger">Failed to load content.</div>');
        }
    });
});
</script>


</body>
</html>
