<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | EDUC Pharmacy System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
  <style>
    body {
      font-family: 'Instrument Sans', sans-serif;
      background-color: #f8f9fa;
    }

    .login-wrapper {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    .login-card {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
      padding: 2.5rem;
      width: 100%;
      max-width: 500px;
    }

    .login-card h2 {
      color: #007bff;
      font-weight: 700;
      margin-bottom: 1rem;
    }

    .form-control:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }

    .btn-primary {
      background-color: #007bff;
      border: none;
    }

    .btn-primary:hover {
      background-color: #0056b3;
    }

    .login-image {
      max-width: 120px;
      display: block;
      margin: 0 auto 1rem;
    }

    .footer-link {
      text-align: center;
      margin-top: 1rem;
    }

    .footer-link a {
      color: #007bff;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>

  <div class="login-wrapper">
    <div class="login-card">
      {{-- Optional illustrative image --}}
      <img src="{{ asset('images/logo.png') }}" alt="EDUC Logo" class="login-image">

      <h2 class="text-center">Login to Your Account</h2>

      <!-- Session Status -->
      @if (session('status'))
        <div class="alert alert-info text-sm">
          {{ session('status') }}
        </div>
      @endif

      <!-- Login Form -->
      <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="form-control">
          @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" id="password" name="password" required class="form-control">
          @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
          <label class="form-check-label" for="remember_me">Remember Me</label>
        </div>

        <div class="d-flex justify-content-between align-items-center">
          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot your password?</a>
          @endif

          <button type="submit" class="btn btn-primary">
            Log in
          </button>
        </div>
      </form>

      <div class="footer-link">
        <p class="mt-3 mb-0">Don't have an account? <a href="{{ route('register') }}">Register</a></p>
      </div>
    </div>
  </div>

</body>
</html>
