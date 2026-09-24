<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In — {{ config('app.name', 'Restro SAAS') }}</title>
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet"/>
    
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
            padding: 1.5rem 1rem;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
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

        .btn-brand-login {
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

        .btn-brand-login:hover {
            opacity: 0.95;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 87, 34, 0.35);
            color: #ffffff;
        }

        .demo-pill {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 1rem;
            margin-top: 1.5rem;
        }

        .btn-quick-fill {
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.3rem 0.75rem;
            border-radius: 20px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #334155;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-quick-fill:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: #fff3e0;
        }

        .divider {
            position: relative;
            text-align: center;
            margin: 1.5rem 0;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            position: relative;
            background: #ffffff;
            padding: 0 0.75rem;
            color: #94a3b8;
            font-size: 0.8rem;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <div class="brand-logo-badge">
            <i class="bi bi-shop"></i>
        </div>
        <h4 class="fw-bold text-dark mb-1">Welcome Back</h4>
        <p class="text-muted small mb-0">Sign in to your restaurant management console</p>
    </div>

    @if(session('status'))
    <div class="alert alert-success alert-dismissible fade show py-2 mb-3 small" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <div class="mb-3">
            <label class="form-label small fw-semibold text-dark">Email Address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" id="emailInput" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', 'admin@restaurant.com') }}"
                       placeholder="admin@restaurant.com" autofocus required/>
            </div>
            @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold text-dark">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" id="passwordInput"
                       class="form-control @error('password') is-invalid @enderror"
                       value="password"
                       placeholder="Enter password" required/>
                <button type="button" class="btn btn-outline-secondary"
                        style="border-radius:0 12px 12px 0;border-left:none;border-color:#e2e8f0;background:#f8fafc;"
                        onclick="togglePass()">
                    <i class="bi bi-eye text-muted" id="eyeIcon"></i>
                </button>
            </div>
            @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                <label class="form-check-label small text-muted" for="remember">Remember me</label>
            </div>
            @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="small text-decoration-none fw-semibold" style="color:var(--primary);">
                Forgot password?
            </a>
            @endif
        </div>

        <button type="submit" class="btn-brand-login">
            <i class="bi bi-box-arrow-in-right"></i> Sign In to Dashboard
        </button>
    </form>

    <!-- Demo Credentials & 1-Click Fill -->
    <div class="demo-pill">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-bold small text-dark"><i class="bi bi-key-fill text-warning me-1"></i> Demo Credentials</span>
            <span class="badge bg-light text-muted border">Pre-seeded</span>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn-quick-fill flex-fill justify-content-center" onclick="fillCreds('admin@restaurant.com', 'password')">
                <i class="bi bi-shield-lock-fill text-primary"></i> Admin User
            </button>
            <button type="button" class="btn-quick-fill flex-fill justify-content-center" onclick="fillCreds('cashier@restaurant.com', 'password')">
                <i class="bi bi-cash-stack text-success"></i> Cashier Staff
            </button>
        </div>
    </div>

    <div class="text-center mt-4">
        <span class="small text-muted">Don't have an account?</span>
        <a href="{{ route('register') }}" class="small fw-bold text-decoration-none ms-1" style="color: var(--primary);">
            Create Free Account
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePass() {
    const inp = document.getElementById('passwordInput');
    const icon = document.getElementById('eyeIcon');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.className = 'bi bi-eye-slash text-muted';
    } else {
        inp.type = 'password';
        icon.className = 'bi bi-eye text-muted';
    }
}

function fillCreds(email, password) {
    document.getElementById('emailInput').value = email;
    document.getElementById('passwordInput').value = password;
}
</script>
</body>
</html>
