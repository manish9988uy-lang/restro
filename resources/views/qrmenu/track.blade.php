<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order | Restaurant</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .timeline { list-style: none; padding: 0; position: relative; }
        .timeline:before { content: ''; position: absolute; top: 0; bottom: 0; left: 20px; width: 2px; background: #dee2e6; }
        .timeline-item { position: relative; padding-left: 50px; margin-bottom: 20px; }
        .timeline-icon { position: absolute; left: 10px; top: 0; width: 22px; height: 22px; border-radius: 50%; background: #0d6efd; border: 4px solid #fff; }
        .timeline-item.active .timeline-icon { background: #198754; box-shadow: 0 0 0 4px rgba(25, 135, 84, 0.2); }
        .timeline-item.pending .timeline-icon { background: #dee2e6; }
    </style>
</head>
<body>
    <div class="container py-5 max-w-sm mx-auto" style="max-width: 500px;">
        <div class="text-center mb-4">
            <h4 class="fw-bold mb-1">Order #{{ $order_id ?? '1024' }}</h4>
            <p class="text-muted">Estimated time: 15-20 mins</p>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <ul class="timeline mb-0">
                    <li class="timeline-item active">
                        <div class="timeline-icon"></div>
                        <h6 class="mb-0 fw-bold">Order Placed</h6>
                        <small class="text-muted">12:30 PM</small>
                    </li>
                    <li class="timeline-item active">
                        <div class="timeline-icon"></div>
                        <h6 class="mb-0 fw-bold">Confirmed by Kitchen</h6>
                        <small class="text-muted">12:32 PM</small>
                    </li>
                    <li class="timeline-item active">
                        <div class="timeline-icon"></div>
                        <h6 class="mb-0 fw-bold text-success">Preparing</h6>
                        <small class="text-success">In progress...</small>
                    </li>
                    <li class="timeline-item pending">
                        <div class="timeline-icon"></div>
                        <h6 class="mb-0 text-muted">Ready to Serve</h6>
                    </li>
                </ul>
            </div>
        </div>

        <a href="{{ url('/qr/menu') }}" class="btn btn-outline-primary w-100 rounded-pill">
            <i class="bi bi-arrow-left me-1"></i>Back to Menu
        </a>
    </div>
</body>
</html>
