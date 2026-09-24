<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Free Account — {{ config('app.name', 'Restro SAAS') }}</title>
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>

    <style>
        :root {
            --primary: #FF5722;
            --primary-hover: #e64a19;
            --primary-gradient: linear-gradient(135deg, #FF5722 0%, #FF8A65 100%);
            --dark: #0f172a;
        }

        * {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: radial-gradient(circle at 10% 20%, rgba(255, 243, 224, 0.4) 0%, rgba(241, 245, 249, 1) 90%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .register-card {
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            border-radius: 28px;
            padding: 2.5rem;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .brand-logo-badge {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            background: var(--primary-gradient);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin: 0 auto 1rem;
            box-shadow: 0 6px 16px rgba(255, 87, 34, 0.3);
        }

        .form-control {
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(255, 87, 34, 0.12);
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
            border: 1.5px solid #e2e8f0;
            border-right: none;
            background: #f8fafc;
            color: #64748b;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .input-group .form-control {
            border-radius: 0 12px 12px 0;
            border-left: none;
        }

        .btn-brand-register {
            width: 100%;
            padding: 0.85rem;
            background: var(--primary-gradient);
            border: none;
            border-radius: 14px;
            color: #ffffff;
            font-weight: 700;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(255, 87, 34, 0.25);
        }

        .btn-brand-register:hover {
            opacity: 0.95;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 87, 34, 0.35);
            color: #ffffff;
        }
    </style>
</head>
<body>

<div class="register-card">
    <div class="text-center mb-4">
        <div class="brand-logo-badge">
            <i class="bi bi-shop"></i>
        </div>
        <h4 class="fw-bold text-dark mb-1">Create Account</h4>
        <p class="text-muted small mb-0">Start your 14-day free restaurant POS trial</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label small fw-semibold text-dark">Full Name</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" placeholder="Restaurant Owner / Manager" required autofocus/>
            </div>
            @error('name')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold text-dark">Email Address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="owner@yourrestaurant.com" required/>
            </div>
            @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold text-dark">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                       placeholder="Create password (min 8 characters)" required/>
            </div>
            @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label small fw-semibold text-dark">Confirm Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                <input type="password" name="password_confirmation" class="form-control"
                       placeholder="Confirm password" required/>
            </div>
        </div>

        <button type="submit" class="btn-brand-register">
            <i class="bi bi-rocket-takeoff-fill"></i> Create Free Account
        </button>
    </form>

    <div class="text-center mt-4">
        <span class="small text-muted">Already registered?</span>
        <a href="{{ route('login') }}" class="small fw-bold text-decoration-none ms-1" style="color: var(--primary);">
            Sign In Here
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
