<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Confirm Password — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <style>
        * { font-family:'Inter',sans-serif; }
        body { min-height:100vh; background:linear-gradient(135deg,#1a1d2e,#2d3149); display:flex; align-items:center; justify-content:center; padding:1rem; }
        .card { border:none; border-radius:24px; max-width:420px; width:100%; padding:2.5rem; box-shadow:0 25px 60px rgba(0,0,0,.35); }
        .form-control { border-radius:12px; border:2px solid #e8eaf0; padding:.75rem 1rem; }
        .form-control:focus { border-color:#FF6B35; box-shadow:0 0 0 3px rgba(255,107,53,.15); }
        .btn-primary { background:#FF6B35; border-color:#FF6B35; border-radius:12px; padding:.8rem; font-weight:700; }
    </style>
</head>
<body>
<div class="card bg-white">
    <h5 class="fw-700 mb-2">Confirm Password</h5>
    <p class="text-muted small mb-4">Please confirm your password before continuing.</p>
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="mb-4">
            <label class="form-label small fw-600">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required/>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary w-100">Confirm</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
