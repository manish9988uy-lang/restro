<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RestaurantPOS — Modern All-in-One Restaurant Management SaaS</title>
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet"/>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>

    <style>
        :root {
            --primary: #FF5722;
            --primary-hover: #e64a19;
            --primary-light: #fff3e0;
            --primary-gradient: linear-gradient(135deg, #FF5722 0%, #FF8A65 100%);
            --dark: #0f172a;
            --dark-card: #1e293b;
            --text-muted: #64748b;
            --bg-light: #f8fafc;
        }

        * {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            box-sizing: border-box;
        }

        body {
            background-color: #ffffff;
            color: #1e293b;
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.3s ease;
        }

        .brand-logo-badge {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--primary-gradient);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 4px 12px rgba(255, 87, 34, 0.3);
        }

        /* Buttons */
        .btn-brand-primary {
            background: var(--primary-gradient);
            color: #ffffff;
            border: none;
            font-weight: 600;
            padding: 0.75rem 1.75rem;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(255, 87, 34, 0.25);
            text-decoration: none;
        }

        .btn-brand-primary:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 87, 34, 0.35);
        }

        .btn-brand-outline {
            background: transparent;
            color: var(--dark);
            border: 1.5px solid #cbd5e1;
            font-weight: 600;
            padding: 0.75rem 1.75rem;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-brand-outline:hover {
            background: #f1f5f9;
            color: var(--primary);
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        /* Hero */
        .hero-section {
            background: radial-gradient(circle at 10% 20%, rgba(255, 243, 224, 0.6) 0%, rgba(248, 250, 252, 1) 90%);
            padding: 5rem 0 4rem 0;
            position: relative;
        }

        .pill-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #ffffff;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            border: 1px solid #fed7aa;
            color: var(--primary);
            font-size: 0.85rem;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(255, 87, 34, 0.1);
            margin-bottom: 1.5rem;
        }

        /* Feature Cards */
        .feature-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .feature-card:hover {
            transform: translateY(-6px);
            border-color: #ffccbc;
            box-shadow: 0 16px 32px rgba(255, 87, 34, 0.08);
        }

        .feature-icon-box {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            color: var(--primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 1.5rem;
            flex-shrink: 0;
        }

        /* Stats Section */
        .stat-box {
            text-align: center;
            padding: 1.5rem;
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: var(--primary-light);
            color: var(--primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 0.75rem;
        }

        /* Pricing */
        .pricing-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            padding: 2.5rem 2rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            position: relative;
        }

        .pricing-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        }

        .pricing-card.popular {
            border: 2px solid var(--primary);
            box-shadow: 0 12px 32px rgba(255, 87, 34, 0.12);
        }

        .popular-badge {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--primary-gradient);
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            padding: 4px 16px;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            box-shadow: 0 4px 12px rgba(255, 87, 34, 0.3);
        }

        .check-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            font-size: 0.95rem;
            color: #334155;
        }

        .check-icon-badge {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #ecfdf5;
            color: #10b981;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        /* Footer */
        .footer-custom {
            background: #0f172a;
            color: #94a3b8;
            padding: 5rem 0 2rem 0;
        }

        .footer-link {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .footer-link:hover {
            color: #ffffff;
        }

        .footer-icon-circle {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #1e293b;
            color: #cbd5e1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .footer-icon-circle:hover {
            background: var(--primary);
            color: #ffffff;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-4 text-dark text-decoration-none" href="{{ url('/') }}">
            <div class="brand-logo-badge">
                <i class="bi bi-shop"></i>
            </div>
            <span>Restro<span style="color: var(--primary);">SAAS</span></span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto align-items-center gap-2">
                <li class="nav-item">
                    <a class="nav-link fw-semibold px-3 text-dark d-flex align-items-center gap-1" href="#features">
                        <i class="bi bi-stars text-primary"></i> Features
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold px-3 text-dark d-flex align-items-center gap-1" href="#stats">
                        <i class="bi bi-graph-up-arrow text-primary"></i> Growth
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold px-3 text-dark d-flex align-items-center gap-1" href="#pricing">
                        <i class="bi bi-tag text-primary"></i> Pricing
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-brand-primary">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-brand-outline">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="btn-brand-primary">
                        <i class="bi bi-person-plus"></i> Get Started
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6">
                <div class="pill-badge">
                    <i class="bi bi-fire"></i> Next-Generation Cloud POS & SaaS
                </div>
                <h1 class="display-4 fw-extrabold text-dark mb-3" style="line-height: 1.15; font-weight: 800;">
                    Supercharge Your <span style="color: var(--primary);">Restaurant</span> Operations
                </h1>
                <p class="lead text-muted mb-4" style="font-size: 1.15rem; line-height: 1.6;">
                    The all-in-one cloud platform for modern dining. Seamlessly control live POS, kitchen displays, table reservations, multi-branch inventory, and staff management from anywhere.
                </p>
                <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                    <a href="{{ route('register') }}" class="btn-brand-primary btn-lg">
                        <i class="bi bi-rocket-takeoff-fill"></i> Start Free 14-Day Trial
                    </a>
                    <a href="#features" class="btn-brand-outline btn-lg">
                        <i class="bi bi-play-circle"></i> Explore Features
                    </a>
                </div>
                <div class="d-flex align-items-center gap-4 text-muted small pt-2">
                    <span class="d-inline-flex align-items-center gap-1">
                        <i class="bi bi-check-circle-fill text-success"></i> No credit card required
                    </span>
                    <span class="d-inline-flex align-items-center gap-1">
                        <i class="bi bi-check-circle-fill text-success"></i> Instant Setup
                    </span>
                </div>
            </div>

            <div class="col-lg-6 text-center">
                <div class="position-relative p-2 rounded-4 shadow-lg bg-white border border-light">
                    <img src="https://images.unsplash.com/photo-1552566626-52f8b828add9?w=800&auto=format&fit=crop&q=80" 
                         class="img-fluid rounded-4" 
                         alt="Restaurant POS System Dashboard"
                         style="max-height: 440px; width: 100%; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Bar -->
<section id="stats" class="py-5" style="background: #f8fafc; border-top: 1px solid #edf2f7; border-bottom: 1px solid #edf2f7;">
    <div class="container">
        <div class="row g-4">
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="stat-icon">
                        <i class="bi bi-shop-window"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">500+</h3>
                    <p class="text-muted small mb-0">Active Restaurants</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="stat-icon">
                        <i class="bi bi-receipt-cutoff"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">2.4M+</h3>
                    <p class="text-muted small mb-0">Orders Processed</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="stat-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">99.9%</h3>
                    <p class="text-muted small mb-0">Guaranteed Uptime</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="stat-icon">
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">4.9/5</h3>
                    <p class="text-muted small mb-0">Customer Rating</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Grid -->
<section id="features" class="py-5">
    <div class="container py-4">
        <div class="text-center max-w-700 mx-auto mb-5">
            <div class="pill-badge">
                <i class="bi bi-lightning-charge-fill"></i> Powerful Modules
            </div>
            <h2 class="fw-bold text-dark mb-3" style="font-weight: 800;">
                Engineered for Peak Restaurant Performance
            </h2>
            <p class="text-muted lead fs-6">
                From fast-casual cafes to large enterprise multi-branch franchises.
            </p>
        </div>

        <div class="row g-4">
            <!-- Feature 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon-box">
                        <i class="bi bi-grid-fill"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">High-Speed POS</h5>
                    <p class="text-muted mb-0">
                        Ultra-fast checkout with barcode scanning, custom item modifiers, split bills, and live table ordering.
                    </p>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon-box">
                        <i class="bi bi-display"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Kitchen Display (KDS)</h5>
                    <p class="text-muted mb-0">
                        Real-time kitchen screens that route food tickets instantly to prep stations, eliminating paper clutter.
                    </p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon-box">
                        <i class="bi bi-boxes"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Smart Inventory & Recipe</h5>
                    <p class="text-muted mb-0">
                        Track raw ingredients down to the gram. Automatic stock deductions as menu items are sold.
                    </p>
                </div>
            </div>

            <!-- Feature 4 -->
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon-box">
                        <i class="bi bi-qr-code-scan"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">QR Digital Menu & Ordering</h5>
                    <p class="text-muted mb-0">
                        Contactless QR code menus for tables. Guests order directly from their phone to kitchen.
                    </p>
                </div>
            </div>

            <!-- Feature 5 -->
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon-box">
                        <i class="bi bi-buildings"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Multi-Branch & Franchise</h5>
                    <p class="text-muted mb-0">
                        Manage multiple restaurant locations, stock transfers, and centralized revenue analytics from one dashboard.
                    </p>
                </div>
            </div>

            <!-- Feature 6 -->
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon-box">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Role Permissions & Staff</h5>
                    <p class="text-muted mb-0">
                        Granular access control for cashiers, waitstaff, managers, and superadmins with automated audit logs.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section id="pricing" class="py-5" style="background: #f8fafc;">
    <div class="container py-4">
        <div class="text-center max-w-700 mx-auto mb-5">
            <div class="pill-badge">
                <i class="bi bi-wallet2"></i> Clear & Transparent Pricing
            </div>
            <h2 class="fw-bold text-dark mb-3" style="font-weight: 800;">
                Choose The Perfect Plan for Your Growth
            </h2>
            <p class="text-muted lead fs-6">
                All plans include free automated cloud backups, SSL encryption, and 24/7 priority support.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Starter -->
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card">
                    <h5 class="fw-bold text-dark mb-1">Starter</h5>
                    <p class="text-muted small mb-3">Ideal for small cafes & food trucks</p>
                    <div class="d-flex align-items-baseline gap-1 mb-4">
                        <span class="display-6 fw-bold text-dark">NPR 2,999</span>
                        <span class="text-muted">/ month</span>
                    </div>

                    <div class="flex-grow-1 mb-4">
                        <div class="check-item">
                            <span class="check-icon-badge"><i class="bi bi-check-lg"></i></span>
                            <span>1 Single Location / Branch</span>
                        </div>
                        <div class="check-item">
                            <span class="check-icon-badge"><i class="bi bi-check-lg"></i></span>
                            <span>Fast POS & Receipt Printing</span>
                        </div>
                        <div class="check-item">
                            <span class="check-icon-badge"><i class="bi bi-check-lg"></i></span>
                            <span>Basic Inventory Tracking</span>
                        </div>
                        <div class="check-item">
                            <span class="check-icon-badge"><i class="bi bi-check-lg"></i></span>
                            <span>Standard Sales Reports</span>
                        </div>
                    </div>

                    <a href="{{ route('register') }}" class="btn-brand-outline w-100">
                        <i class="bi bi-arrow-right-circle"></i> Get Started
                    </a>
                </div>
            </div>

            <!-- Professional -->
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card popular">
                    <div class="popular-badge">
                        <i class="bi bi-award-fill"></i> Most Popular
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Professional</h5>
                    <p class="text-muted small mb-3">For bustling dining restaurants</p>
                    <div class="d-flex align-items-baseline gap-1 mb-4">
                        <span class="display-6 fw-bold text-dark">NPR 6,999</span>
                        <span class="text-muted">/ month</span>
                    </div>

                    <div class="flex-grow-1 mb-4">
                        <div class="check-item">
                            <span class="check-icon-badge"><i class="bi bi-check-lg"></i></span>
                            <span>Up to 5 Restaurant Branches</span>
                        </div>
                        <div class="check-item">
                            <span class="check-icon-badge"><i class="bi bi-check-lg"></i></span>
                            <span>Live Kitchen Display (KDS)</span>
                        </div>
                        <div class="check-item">
                            <span class="check-icon-badge"><i class="bi bi-check-lg"></i></span>
                            <span>Full Recipe & Ingredient Control</span>
                        </div>
                        <div class="check-item">
                            <span class="check-icon-badge"><i class="bi bi-check-lg"></i></span>
                            <span>QR Code Digital Ordering</span>
                        </div>
                        <div class="check-item">
                            <span class="check-icon-badge"><i class="bi bi-check-lg"></i></span>
                            <span>Staff Attendance & Payroll</span>
                        </div>
                    </div>

                    <a href="{{ route('register') }}" class="btn-brand-primary w-100">
                        <i class="bi bi-lightning-fill"></i> Start 14-Day Free Trial
                    </a>
                </div>
            </div>

            <!-- Enterprise -->
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card">
                    <h5 class="fw-bold text-dark mb-1">Enterprise</h5>
                    <p class="text-muted small mb-3">For regional restaurant chains & franchises</p>
                    <div class="d-flex align-items-baseline gap-1 mb-4">
                        <span class="display-6 fw-bold text-dark">NPR 14,999</span>
                        <span class="text-muted">/ month</span>
                    </div>

                    <div class="flex-grow-1 mb-4">
                        <div class="check-item">
                            <span class="check-icon-badge"><i class="bi bi-check-lg"></i></span>
                            <span>Unlimited Branches & Terminals</span>
                        </div>
                        <div class="check-item">
                            <span class="check-icon-badge"><i class="bi bi-check-lg"></i></span>
                            <span>Centralized Inter-branch Transfers</span>
                        </div>
                        <div class="check-item">
                            <span class="check-icon-badge"><i class="bi bi-check-lg"></i></span>
                            <span>Dedicated Account Manager</span>
                        </div>
                        <div class="check-item">
                            <span class="check-icon-badge"><i class="bi bi-check-lg"></i></span>
                            <span>Custom API & Payment Integrations</span>
                        </div>
                    </div>

                    <a href="{{ route('register') }}" class="btn-brand-outline w-100">
                        <i class="bi bi-chat-dots"></i> Contact Sales
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call To Action -->
<section class="py-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff;">
    <div class="container py-4 text-center">
        <div class="pill-badge" style="background: rgba(255, 87, 34, 0.15); border-color: rgba(255, 87, 34, 0.4); color: #ff8a65;">
            <i class="bi bi-rocket"></i> Launch Today
        </div>
        <h2 class="display-5 fw-bold mb-3" style="font-weight: 800;">
            Ready to Transform Your Restaurant?
        </h2>
        <p class="lead text-secondary max-w-600 mx-auto mb-4" style="color: #94a3b8 !important;">
            Get started in under 2 minutes. No credit card required.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('register') }}" class="btn-brand-primary btn-lg">
                <i class="bi bi-arrow-right-circle-fill"></i> Create Free Account
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer-custom">
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 fw-bold fs-4 text-white mb-3">
                    <div class="brand-logo-badge">
                        <i class="bi bi-shop"></i>
                    </div>
                    <span>Restro<span style="color: var(--primary);">SAAS</span></span>
                </div>
                <p class="text-secondary small mb-4">
                    The complete cloud restaurant management solution for POS, KDS, inventory, tables, and multi-branch management.
                </p>
                <div class="d-flex align-items-center gap-2">
                    <a href="#" class="footer-icon-circle"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="footer-icon-circle"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="footer-icon-circle"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="footer-icon-circle"><i class="bi bi-github"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <h6 class="text-white fw-bold mb-3">Product</h6>
                <div class="d-flex flex-column">
                    <a href="#features" class="footer-link"><i class="bi bi-chevron-right small text-primary"></i> Features</a>
                    <a href="#pricing" class="footer-link"><i class="bi bi-chevron-right small text-primary"></i> Pricing</a>
                    <a href="{{ route('login') }}" class="footer-link"><i class="bi bi-chevron-right small text-primary"></i> Web POS</a>
                    <a href="{{ route('register') }}" class="footer-link"><i class="bi bi-chevron-right small text-primary"></i> Free Trial</a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3">Modules</h6>
                <div class="d-flex flex-column">
                    <span class="footer-link"><i class="bi bi-check2 text-primary"></i> Kitchen Display System</span>
                    <span class="footer-link"><i class="bi bi-check2 text-primary"></i> QR Code Ordering</span>
                    <span class="footer-link"><i class="bi bi-check2 text-primary"></i> Inventory & Recipes</span>
                    <span class="footer-link"><i class="bi bi-check2 text-primary"></i> Multi-branch Franchises</span>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3">Get in Touch</h6>
                <div class="d-flex flex-column gap-2 text-secondary small">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt-fill text-primary"></i> Global Cloud Infrastructure
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-envelope-fill text-primary"></i> support@restrosaas.com
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-headset text-primary"></i> 24/7 Technical Support
                    </div>
                </div>
            </div>
        </div>

        <hr style="border-color: #334155;">
        <div class="d-flex flex-wrap justify-content-between align-items-center pt-3 small text-secondary">
            <span>&copy; {{ date('Y') }} RestroSAAS. All rights reserved.</span>
            <span>Built for high-performance hospitality.</span>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
