<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pharmacy Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            height: 100vh;
            background-color: #007bff;
            color: #fff;
            padding-top: 1rem;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 0.75rem 1rem;
            display: block;
        }

        .sidebar a:hover {
            background-color: #0056b3;
        }

        .main-content {
            margin-left: 220px;
            padding: 2rem;
        }

        .navbar {
            margin-left: 220px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .card-title {
            color: #007bff;
        }
    </style>
</head>
<body>

    @include('partials.sidebar')

    <nav class="navbar navbar-expand-lg px-4">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h5">Dashboard</span>
        </div>
    </nav>

    <div class="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
