<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>EDUC Pharmacy Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
  <style>
    body {
      font-family: 'Instrument Sans', sans-serif;
      background-color: #f8f9fa;
      color: #1b1b18;
    }

    /* Navbar */
    .navbar {
      background-color: #007bff;
      padding: 0.7rem 1rem;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      color: white;
      font-weight: 600;
      font-size: 1.3rem;
    }

    .navbar-brand img {
      height: 40px;
      width: auto;
    }

    .navbar .nav-link {
      color: white !important;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .navbar .nav-link:hover {
      color: #ffe082 !important;
    }

    /* Hero Section */
    .hero-section {
      padding: 4rem 1rem;
      background: linear-gradient(135deg, #e3f2fd, #ffffff);
    }

    .hero-content h1 {
      color: #0047ab;
      font-weight: 700;
      font-size: 2.5rem;
    }

    .hero-content p {
      font-size: 1.1rem;
      color: #4a4a4a;
      line-height: 1.7;
    }

    .features {
      list-style: none;
      padding-left: 0;
      margin-top: 1.5rem;
    }

    .features li {
      margin-bottom: 0.75rem;
      font-size: 1rem;
      animation: fadeInUp 0.6s ease forwards;
      opacity: 0;
    }

    .features li:nth-child(1) { animation-delay: 0.2s; }
    .features li:nth-child(2) { animation-delay: 0.4s; }
    .features li:nth-child(3) { animation-delay: 0.6s; }
    .features li:nth-child(4) { animation-delay: 0.8s; }

    .features li::before {
      content: "✔";
      color: #007bff;
      margin-right: 0.5rem;
    }

    /* Button */
    .btn-get-started {
      background-color: #007bff;
      border: none;
      padding: 0.75rem 1.75rem;
      font-size: 1rem;
      margin-top: 2rem;
      color: #fff;
      border-radius: 50px;
      box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
      transition: all 0.3s ease-in-out;
    }

    .btn-get-started:hover {
      background-color: #0056b3;
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(0, 86, 179, 0.3);
    }

    /* Image */
    .hero-img {
      max-width: 100%;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.08);
      animation: fadeIn 1s ease forwards;
      opacity: 0;
    }

    /* Footer */
    footer {
      text-align: center;
      padding: 1rem;
      font-size: 0.9rem;
      color: #888;
      background: #f1f1f1;
      margin-top: 3rem;
    }

    /* Animations */
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="{{ asset('images/logo.png') }}" alt="EDUC Logo">
        EDUC PHARMACY
      </a>
      <div class="ms-auto d-flex gap-3">
        <a class="nav-link" href="{{ route('login') }}">Login</a>
        <a class="nav-link" href="{{ route('register') }}">Register</a>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container">
      <div class="row align-items-center">
        <!-- Text Content -->
        <div class="col-md-6 hero-content">
          <h1>Welcome to EDUC Pharmacy Management System</h1>
          <p class="mt-3">Your trusted digital partner for streamlined pharmacy operations, stock control, prescription tracking, and automated billing.</p>

          <ul class="features">
            <li>Inventory Tracking Made Easy</li>
            <li>Automated Sales and Billing</li>
            <li>Role-Based User Access</li>
            <li>Real-time Reporting & Analytics</li>
          </ul>

          <a href="{{ route('login') }}" class="btn btn-get-started">Get Started</a>
        </div>

        <!-- Image -->
        <div class="col-md-6 text-center mt-4 mt-md-0">
          <img src="{{ asset('images/educpic.jpg') }}" alt="Pharmacy illustration" class="hero-img">
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    &copy; 2025 EDUC | Empowering Pharmacy Management with Technology
  </footer>

</body>
</html>
