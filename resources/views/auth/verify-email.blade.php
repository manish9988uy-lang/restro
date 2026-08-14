<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Verify Email — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <style>
        * { font-family:'Inter',sans-serif; }
        body { min-height:100vh; background:linear-gradient(135deg,#1a1d2e,#2d3149); display:flex; align-items:center; justify-content:center; padding:1rem; }
        .card { border:none; border-radius:24px; max-width:440px; width:100%; padding:2.5rem; box-shadow:0 25px 60px rgba(0,0,0,.35); text-align:center; }
        .btn-primary { background:#FF6B35; border-color:#FF6B35; border-radius:12px; padding:.75rem 2rem; font-weight:700; }
    </style>
</head>
<body>
<div class="card bg-white">
    <div style="font-size:3rem;margin-bottom:1rem;">📧</div>
    <h5 class="fw-700 mb-2">Verify Email</h5>
    <p class="text-muted small mb-4">Thanks for signing up! Please verify your email address by clicking the link we sent you.</p>
    @if(session('status') === 'verification-link-sent')
    <div class="alert alert-success py-2 mb-3">A new verification link has been sent.</div>
    @endif
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary mb-3">Resend Verification Email</button>
    </form>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-secondary btn-sm">Logout</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
