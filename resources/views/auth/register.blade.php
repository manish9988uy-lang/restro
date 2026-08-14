<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Register — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { min-height:100vh; background:linear-gradient(135deg,#1a1d2e,#2d3149,#1a1d2e); display:flex; align-items:center; justify-content:center; padding:1rem; }
        .card { border:none; border-radius:24px; box-shadow:0 25px 60px rgba(0,0,0,.35); max-width:440px; width:100%; padding:2.5rem; }
        .form-control { border-radius:12px; border:2px solid #e8eaf0; padding:.75rem 1rem; }
        .form-control:focus { border-color:#FF6B35; box-shadow:0 0 0 3px rgba(255,107,53,.15); }
        .btn-primary { background:#FF6B35; border-color:#FF6B35; border-radius:12px; padding:.8rem; font-weight:700; }
        .btn-primary:hover { background:#e5521a; border-color:#e5521a; }
    </style>
</head>
<body>
<div class="card bg-white">
    <div class="text-center mb-4">
        <div style="width:52px;height:52px;background:linear-gradient(135deg,#FF6B35,#ff9f7c);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin:0 auto .75rem;">
            <i class="bi bi-shop text-white"></i>
        </div>
        <h5 class="fw-700 mb-1">Create Account</h5>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label small fw-600">Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" required autofocus/>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label small fw-600">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required/>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label small fw-600">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required/>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="form-label small fw-600">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required/>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>

        <div class="divider" style="position: relative; text-align: center; margin: 1.25rem 0;">
            <div style="content: ''; position: absolute; top: 50%; left: 0; right: 0; height: 1px; background: #e8eaf0;"></div>
            <span style="position: relative; background: #fff; padding: 0 .75rem; color: #adb5bd; font-size: .8rem;">Or continue with</span>
        </div>

        <div class="d-grid gap-2 mb-3">
            <a href="{{ route('socialite.redirect', 'google') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-google"></i> Google
            </a>
            <a href="{{ route('socialite.redirect', 'github') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-github"></i> GitHub
            </a>
        </div>

        <p class="text-center small mt-3 mb-0">
            Already have an account?
            <a href="{{ route('login') }}" style="color:#FF6B35;text-decoration:none;">Sign in</a>
        </p>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
