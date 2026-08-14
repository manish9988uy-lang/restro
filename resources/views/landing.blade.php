<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RestaurantPOS - Modern Restaurant Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <style>
        :root {
            --primary: #FF6B35;
            --primary-dark: #e5521a;
            --dark: #1a1d2e;
        }
        body { font-family: 'Inter', sans-serif; background: #fff; color: var(--dark); }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover, .btn-primary:focus { background: var(--primary-dark); border-color: var(--primary-dark); }
        .hero {
            background: linear-gradient(135deg, #fdfbfb 0%, #f4f6fb 100%);
            min-height: 100vh;
        }
        .hero-content {
            padding: 6rem 0;
        }
        .feature-icon {
            width: 70px; height: 70px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            color: var(--primary);
            font-size: 1.8rem;
            margin-bottom: 1.2rem;
        }
        .testimonial {
            background: #f8f9fc;
            border-radius: 16px;
        }
        .pricing-card {
            border-radius: 20px;
            transition: transform .2s, box-shadow .2s;
        }
        .pricing-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,.1);
        }
        .pricing-card.featured {
            border: 2px solid var(--primary);
            position: relative;
        }
        .pricing-card.featured::before {
            content: 'Most Popular';
            position: absolute;
            top: -12px; left: 50%;
            transform: translateX(-50%);
            background: var(--primary);
            color: white;
            padding: 4px 16px;
            border-radius: 20px;
            font-size: .8rem;
            font-weight: 600;
        }
        .navbar {
            background: rgba(255,255,255,.9);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
            <div class="d-flex align-items-center justify-content-center" style="width:40px;height:40px;border-radius:10px;background:var(--primary);color:white;font-size:1.2rem;">
                <i class="bi bi-shop"></i>
            </div>
            <span class="fw-bold">Restaurant<span style="color:var(--primary);">POS</span></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#features">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#pricing">Pricing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact</a>
                </li>
                @auth
                    <li class="nav-item ms-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">Dashboard</a>
                    </li>
                @else
                    <li class="nav-item ms-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<section class="hero">
    <div class="container hero-content">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="fw-bold mb-4" style="font-size:3.2rem;line-height:1.1;">
                    Manage Your Restaurant <span style="color:var(--primary);">Effortlessly</span>
                </h1>
                <p class="lead mb-5 text-muted">
                    A complete SaaS solution for restaurant management including POS, inventory, orders, and more.
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5 rounded-pill">Start Free Trial</a>
                    <a href="#features" class="btn btn-outline-dark btn-lg px-5 rounded-pill">Learn More</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-4 rounded-3" style="background:linear-gradient(135deg, #fff 0%, #f8f9fc 100%);box-shadow:0 10px 40px rgba(0,0,0,.08);">
                    <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&h=600&fit=crop" class="img-fluid rounded-3" alt="Restaurant POS">
                </div>
            </div>
        </div>
    </div>
</section>

<section id="features" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Powerful Features for Your Restaurant</h2>
            <p class="text-muted">Everything you need to run your restaurant smoothly</p>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="bi bi-grid-1x2-fill"></i>
                </div>
                <h4 class="fw-bold mb-2">Modern POS</h4>
                <p class="text-muted">Fast and intuitive point of sale system with table management.</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h4 class="fw-bold mb-2">Inventory Management</h4>
                <p class="text-muted">Track ingredients, manage stock, and reduce food waste.</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="bi bi-bar-chart-line"></i>
                </div>
                <h4 class="fw-bold mb-2">Reports & Analytics</h4>
                <p class="text-muted">Get insights into your sales, customers, and performance.</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="bi bi-people"></i>
                </div>
                <h4 class="fw-bold mb-2">Employee Management</h4>
                <p class="text-muted">Manage staff, attendance, and payroll with ease.</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="bi bi-building"></i>
                </div>
                <h4 class="fw-bold mb-2">Multi-Branch Support</h4>
                <p class="text-muted">Manage multiple restaurant branches from one dashboard.</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="bi bi-shield-star"></i>
                </div>
                <h4 class="fw-bold mb-2">SaaS Ready</h4>
                <p class="text-muted">Multi-tenant architecture with tenant management.</p>
            </div>
        </div>
    </div>
</section>

<section id="pricing" class="py-5" style="background:#f8f9fc;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Simple Pricing Plans</h2>
            <p class="text-muted">Choose the plan that fits your restaurant</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="pricing-card bg-white p-5 h-100">
                    <h5 class="fw-bold mb-3">Starter</h5>
                    <h2 class="fw-bold mb-3">$29<span class="text-muted" style="font-size:1rem;">/mo</span></h2>
                    <p class="text-muted mb-4">Perfect for small cafes</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> 1 Branch</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Basic POS</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Inventory Tracking</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Email Support</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 rounded-pill">Get Started</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pricing-card featured bg-white p-5 h-100">
                    <h5 class="fw-bold mb-3">Professional</h5>
                    <h2 class="fw-bold mb-3">$79<span class="text-muted" style="font-size:1rem;">/mo</span></h2>
                    <p class="text-muted mb-4">For growing restaurants</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Up to 5 Branches</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Advanced POS</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Full Inventory</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Reports & Analytics</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Priority Support</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn btn-primary w-100 rounded-pill">Get Started</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pricing-card bg-white p-5 h-100">
                    <h5 class="fw-bold mb-3">Enterprise</h5>
                    <h2 class="fw-bold mb-3">Custom</h2>
                    <p class="text-muted mb-4">For large chains</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Unlimited Branches</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> All Features</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Dedicated Support</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Custom Integrations</li>
                    </ul>
                    <a href="#contact" class="btn btn-outline-primary w-100 rounded-pill">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 text-center">
    <div class="container">
        <h2 class="fw-bold mb-3">Ready to Get Started?</h2>
        <p class="text-muted mb-4">Join thousands of restaurants using RestaurantPOS</p>
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5 rounded-pill">Start Your Free Trial</a>
    </div>
</section>

<footer class="bg-dark text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3">RestaurantPOS</h5>
                <p class="text-muted">Modern restaurant management system for the 21st century.</p>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="#features" class="text-muted text-decoration-none">Features</a></li>
                    <li><a href="#pricing" class="text-muted text-decoration-none">Pricing</a></li>
                    <li><a href="#contact" class="text-muted text-decoration-none">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3">Contact</h5>
                <ul class="list-unstyled text-muted">
                    <li class="mb-2"><i class="bi bi-envelope me-2"></i> hello@restaurantpos.com</li>
                    <li class="mb-2"><i class="bi bi-telephone me-2"></i> +1 (555) 123-4567</li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary">
        <p class="text-center text-muted mb-0">&copy; {{ date('Y') }} RestaurantPOS. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
