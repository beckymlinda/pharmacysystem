<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register | EDUC Pharmacy System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
  <style>
    body {
      font-family: 'Instrument Sans', sans-serif;
      background-color: #f8f9fa;
    }

    .register-wrapper {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    .register-card {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
      padding: 2.5rem;
      width: 100%;
      max-width: 550px;
    }

    .register-card h2 {
      color: #007bff;
      font-weight: 700;
      margin-bottom: 1.5rem;
      text-align: center;
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

    .register-image {
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

  <div class="register-wrapper">
    <div class="register-card">
      <img src="{{ asset('images/logo.png') }}" alt="EDUC Logo" class="register-image">

      <h2>Create Your Account</h2>

      <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
          <label for="name" class="form-label">Full Name</label>
          <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
          @error('name')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Email -->
        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
          @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input id="password" type="password" class="form-control" name="password" required>
          @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
          <label for="password_confirmation" class="form-label">Confirm Password</label>
          <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
          @error('password_confirmation')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-between align-items-center">
          <a href="{{ route('login') }}" class="text-decoration-none">Already registered?</a>
          <button type="submit" class="btn btn-primary">
            Register
          </button>
        </div>
      </form>

    </div>
  </div>

</body>
</html>
