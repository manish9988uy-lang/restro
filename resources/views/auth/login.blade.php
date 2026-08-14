<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1d2e 0%, #2d3149 50%, #1a1d2e 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 1rem;
        }
        .login-card {
            width: 100%; max-width: 420px;
            background: #fff;
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 25px 60px rgba(0,0,0,.35);
        }
        .brand-icon {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, #FF6B35, #ff9f7c);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; margin: 0 auto 1rem;
        }
        .form-control {
            border-radius: 12px; border: 2px solid #e8eaf0;
            padding: .75rem 1rem; font-size: .9rem;
            transition: border-color .2s;
        }
        .form-control:focus {
            border-color: #FF6B35; box-shadow: 0 0 0 3px rgba(255,107,53,.15);
        }
        .input-group-text {
            border-radius: 12px 0 0 12px;
            border: 2px solid #e8eaf0; border-right: none;
            background: #f8f9fc; color: #8a94a6;
        }
        .input-group .form-control { border-radius: 0 12px 12px 0; border-left: none; }
        .btn-login {
            width: 100%; padding: .85rem;
            background: linear-gradient(135deg, #FF6B35, #e5521a);
            border: none; border-radius: 12px;
            color: #fff; font-weight: 700; font-size: 1rem;
            cursor: pointer; transition: opacity .2s;
        }
        .btn-login:hover { opacity: .9; }
        .divider { position: relative; text-align: center; margin: 1.25rem 0; }
        .divider::before {
            content: ''; position: absolute; top: 50%; left: 0;
            right: 0; height: 1px; background: #e8eaf0;
        }
        .divider span {
            position: relative; background: #fff;
            padding: 0 .75rem; color: #adb5bd; font-size: .8rem;
        }
        .demo-badge {
            background: #f8f9fc; border-radius: 10px;
            padding: .75rem 1rem; font-size: .8rem;
        }
    </style>
</head>
<body>
<div class="login-card">
    <div class="text-center mb-4">
        <div class="brand-icon"><i class="bi bi-shop text-white"></i></div>
        <h4 class="fw-700 mb-1">RestaurantPOS</h4>
        <p class="text-muted small mb-0">Sign in to your account</p>
    </div>

    @if(session('status'))
    <div class="alert alert-success alert-dismissible fade show py-2 mb-3" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label small fw-600">Email Address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', 'admin@restaurant.com') }}"
                       placeholder="Enter your email" autofocus required/>
            </div>
            @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label small fw-600">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" id="passwordInput"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Enter your password" required/>
                <button type="button" class="btn btn-outline-secondary"
                        style="border-radius:0 12px 12px 0;border-left:none;border-color:#e8eaf0;"
                        onclick="togglePass()">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
            </div>
            @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small" for="remember">Remember me</label>
            </div>
            @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="small text-decoration-none" style="color:#FF6B35;">
                Forgot password?
            </a>
            @endif
        </div>

        <button type="submit" class="btn-login">
            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
        </button>
    </form>

    <div class="divider"><span>Or continue with</span></div>

    <div class="d-grid gap-2 mb-3">
        <a href="{{ route('socialite.redirect', 'google') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-google"></i> Google
        </a>
        <a href="{{ route('socialite.redirect', 'github') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-github"></i> GitHub
        </a>
    </div>

    <div class="divider"><span>Demo Credentials</span></div>

    <div class="demo-badge">
        <div class="d-flex justify-content-between mb-1">
            <span class="text-muted">Email</span>
            <code class="small">admin@restaurant.com</code>
        </div>
        <div class="d-flex justify-content-between">
            <span class="text-muted">Password</span>
            <code class="small">password</code>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePass() {
    const inp = document.getElementById('passwordInput');
    const icon = document.getElementById('eyeIcon');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        inp.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
</body>
</html>
