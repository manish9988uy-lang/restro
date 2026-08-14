<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Forgot Password — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <style>
        * { font-family:'Inter',sans-serif; }
        body { min-height:100vh; background:linear-gradient(135deg,#1a1d2e,#2d3149,#1a1d2e); display:flex; align-items:center; justify-content:center; padding:1rem; }
        .card { border:none; border-radius:24px; box-shadow:0 25px 60px rgba(0,0,0,.35); max-width:420px; width:100%; padding:2.5rem; }
        .form-control { border-radius:12px; border:2px solid #e8eaf0; padding:.75rem 1rem; }
        .form-control:focus { border-color:#FF6B35; box-shadow:0 0 0 3px rgba(255,107,53,.15); }
        .btn-primary { background:#FF6B35; border-color:#FF6B35; border-radius:12px; padding:.8rem; font-weight:700; }
    </style>
</head>
<body>
<div class="card bg-white">
    <div class="text-center mb-4">
        <div style="width:52px;height:52px;background:linear-gradient(135deg,#FF6B35,#ff9f7c);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin:0 auto .75rem;">
            <i class="bi bi-lock text-white"></i>
        </div>
        <h5 class="fw-700 mb-1">Forgot Password</h5>
        <p class="text-muted small">Enter your email to reset your password</p>
    </div>
    @if(session('status'))
    <div class="alert alert-success py-2">{{ session('status') }}</div>
    @endif
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label small fw-600">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required autofocus/>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
        <p class="text-center small mt-3 mb-0">
            <a href="{{ route('login') }}" style="color:#FF6B35;text-decoration:none;">
                <i class="bi bi-arrow-left me-1"></i>Back to login
            </a>
        </p>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
