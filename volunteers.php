<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Portal | Christ Ekklesia Fellowship Chapel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-purple: #60379e;
            --secondary-purple: #7e4ba8;
            --light-purple: #f3e8ff;
            --accent-purple: #8b5cf6;
            --accent-gold: #f59e0b;
            --dark-purple: #312e81;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --white: #ffffff;
            --off-white: #f9fafb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: var(--off-white);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Login Page Styles */
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            position: relative;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M 20 0 L 0 0 0 20" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .login-card {
            background: var(--white);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            padding: 3rem;
            max-width: 450px;
            width: 100%;
            position: relative;
            z-index: 2;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-logo i {
            font-size: 4rem;
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-purple);
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: var(--text-light);
            margin-bottom: 2rem;
        }

        /* Dashboard Styles */
        .dashboard-page {
            display: none;
        }

        .sidebar {
            background: var(--white);
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(96, 55, 158, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
        }

        .sidebar-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-purple);
        }

        .sidebar-nav {
            padding: 1.5rem 0;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: var(--text-dark);
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 0;
        }

        .nav-link:hover, .nav-link.active {
            background: linear-gradient(90deg, var(--light-purple), transparent);
            color: var(--primary-purple);
            border-right: 3px solid var(--primary-purple);
        }

        .nav-link i {
            width: 20px;
            margin-right: 1rem;
        }

        .main-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
        }

        .topbar {
            background: var(--white);
            border-radius: 16px;
            padding: 1rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(96, 55, 158, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 600;
        }

        /* Dashboard Cards */
        .dashboard-card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(96, 55, 158, 0.08);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(96, 55, 158, 0.05);
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(96, 55, 158, 0.12);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            color: var(--white);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            transform: rotate(45deg);
        }

        .stat-card .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }

        .stat-card .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-card .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* Form Styles */
        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
        }

        .form-control, .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--white);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-purple);
            box-shadow: 0 0 0 0.2rem rgba(139, 92, 246, 0.25);
        }

        /* Button Styles */
        .btn {
            border-radius: 12px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            color: var(--white);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-purple), var(--primary-purple));
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(96, 55, 158, 0.3);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-purple);
            color: var(--primary-purple);
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--primary-purple);
            color: var(--white);
            transform: translateY(-2px);
        }

        .btn-logout {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: var(--white);
        }

        .btn-logout:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-2px);
        }

        /* Profile Card */
        .profile-card {
            text-align: center;
            padding: 2.5rem;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 3rem;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 30px rgba(96, 55, 158, 0.3);
        }

        /* Recent Activities */
        .activity-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--light-purple);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-purple);
            margin-right: 1rem;
        }

        .activity-content {
            flex: 1;
        }

        .activity-time {
            font-size: 0.875rem;
            color: var(--text-light);
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .topbar {
                padding: 1rem;
            }

            .mobile-menu-btn {
                display: block !important;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .login-card {
                margin: 1rem;
                padding: 2rem;
            }
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--primary-purple);
        }

        /* Content Sections */
        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        /* Alert Styles */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dashboard-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }
        .login-logo-img {
            width: 100px;
            height: 100px;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <!-- Login Page -->
    <div id="login-page" class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="login-card mx-auto">
                        <div class="login-logo">
                            <img src="assets/images/logo.png" alt="CEFC Logo" class="login-logo-img">
                            <h2 class="login-title">Volunteer Portal</h2>
                            <p class="login-subtitle">Christ Ekklesia Fellowship Chapel</p>
                        </div>
                        
                        <div id="login-alerts"></div>
                        
                        <form id="login-form">
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email Address
                                </label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <input type="password" class="form-control" id="password" required>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Sign In
                            </button>
                        </form>

                        <form id="signup-form" style="display:none; margin-top:2rem;">
                            <h2 class="text-center">Volunteer Signup</h2>
                            <div class="mb-3">
                                <label for="signup-name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="signup-name" required>
                            </div>
                            <div class="mb-3">
                                <label for="signup-email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="signup-email" required>
                            </div>
                            <div class="mb-3">
                                <label for="signup-phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="signup-phone">
                            </div>
                            <div class="mb-3">
                                <label for="signup-ministry" class="form-label">Ministry</label>
                                <select class="form-select" id="signup-ministry" required>
                                    <option value="">-- Select Ministry --</option>
                                    <option>Christ Amplified Ministry</option>
                                    <option>Children's Ministry</option>
                                    <option>Sound & Media Ministry</option>
                                    <option>Ushering Ministry</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="signup-password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="signup-password" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100 mb-2">Sign Up</button>
                            <button type="button" class="btn btn-link w-100" id="cancel-signup">Cancel</button>
                            <div id="signup-alerts" class="mt-2"></div>
                        </form>

                        <form id="otp-form" style="display:none; margin-top:2rem;">
                            <h2 class="text-center">Admin OTP Verification</h2>
                            <div class="mb-3">
                                <label for="otp-email" class="form-label">Volunteer Email</label>
                                <input type="email" class="form-control" id="otp-email" required>
                            </div>
                            <div class="mb-3">
                                <label for="otp-code" class="form-label">OTP Code</label>
                                <input type="text" class="form-control" id="otp-code" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-2">Verify</button>
                            <button type="button" class="btn btn-link w-100" id="cancel-otp">Cancel</button>
                            <div id="otp-alerts" class="mt-2"></div>
                        </form>

                        <div class="text-center">
                            <p class="mb-2">
                                <a href="#" class="text-decoration-none" onclick="showForgotPassword()">Forgot Password?</a>
                            </p>
                            <p class="text-muted">
                                New volunteer? <a href="#" class="text-decoration-none" id="show-signup-link">Register Here</a>
                            </p>
                        </div>

                        <div class="text-center mt-3">
                            <a href="#" id="show-otp-link">Verify OTP</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Page -->
    <div id="dashboard-page" class="dashboard-page">
        <!-- Mobile Overlay -->
        <!-- Commitment Form Download -->
        <div class="mb-3 text-end">
            <a href="assets/commitment_form.pdf" class="btn btn-outline-primary" download>
                <i class="fa fa-file-pdf-o"></i> Download Commitment Form (PDF)
            </a>
        </div>
        <div class="overlay" id="overlay"></div>
        
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                <img src="assets/images/logo.png" alt="CEFC Logo" class="login-logo-img">
                    CEFC Portal
                </div>
            </div>
            <div class="sidebar-nav">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" data-section="dashboard">
                            <i class="fas fa-tachometer-alt"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-section="profile">
                            <i class="fas fa-user"></i>My Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-section="volunteer">
                            <i class="fas fa-hands-helping"></i>Volunteer Forms
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-section="schedule">
                            <i class="fas fa-calendar-alt"></i>My Schedule
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-section="messages">
                            <i class="fas fa-comments"></i>Messages
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-section="resources">
                            <i class="fas fa-book"></i>Resources
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="topbar">
                <div class="d-flex align-items-center">
                    <button class="mobile-menu-btn me-3" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h4 class="mb-0" id="page-title">Dashboard</h4>
                </div>
                <div class="user-info">
                    <div class="text-end me-3 d-none d-md-block">
                        <div class="fw-bold" id="user-name">John Volunteer</div>
                        <small class="text-muted" id="user-role">Active Volunteer</small>
                    </div>
                    <div class="user-avatar" id="user-avatar">JV</div>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle ms-2" data-bs-toggle="dropdown">
                            <i class="fas fa-cog"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" data-section="profile"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="logout()"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="content-section active" id="dashboard-content">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-number">24</div>
                        <div class="stat-label">Hours This Month</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-number">8</div>
                        <div class="stat-label">Events Attended</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <div class="stat-number">2</div>
                        <div class="stat-label">Years of Service</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="stat-number">156</div>
                        <div class="stat-label">Lives Impacted</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="dashboard-card">
                            <h5 class="mb-4">Recent Activities</h5>
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="fw-bold">Sunday Service Volunteer</div>
                                    <div class="text-muted">Helped with children's ministry</div>
                                    <div class="activity-time">2 days ago</div>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-upload"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="fw-bold">Commitment Form Updated</div>
                                    <div class="text-muted">Updated availability schedule</div>
                                    <div class="activity-time">1 week ago</div>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="fw-bold">Community Outreach</div>
                                    <div class="text-muted">Food distribution volunteer</div>
                                    <div class="activity-time">2 weeks ago</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="dashboard-card">
                            <h5 class="mb-4">Upcoming Events</h5>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item px-0 border-0">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <div class="fw-bold">Youth Ministry</div>
                                            <small class="text-muted">Saturday 2:00 PM</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">Tomorrow</span>
                                    </div>
                                </div>
                                <div class="list-group-item px-0 border-0">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <div class="fw-bold">Sunday Service</div>
                                            <small class="text-muted">Sunday 9:00 AM</small>
                                        </div>
                                        <span class="badge bg-success rounded-pill">This Week</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="content-section" id="profile-content">
                <div class="dashboard-card profile-card">
                    <div class="profile-avatar">JV</div>
                    <h3>John Volunteer</h3>
                    <p class="text-muted">volunteer@cefc.org</p>
                    <p><span class="badge bg-success">Active Volunteer</span></p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" value="John Volunteer" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="volunteer@cefc.org" readonly>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" value="+1 (555) 123-4567" readonly>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Join Date</label>
                            <input type="text" class="form-control" value="January 2022" readonly>
                        </div>
                    </div>
                    
                    <button class="btn btn-primary mt-4">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </button>
                </div>
            </div>

            <!-- Volunteer Forms Content -->
            <div class="content-section" id="volunteer-content">
                <div class="dashboard-card">
                    <h5 class="mb-4">
                        <i class="fas fa-hands-helping me-2"></i>
                        Volunteer Management
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-download fa-3x text-primary mb-3"></i>
                                    <h6>Download Forms</h6>
                                    <p class="text-muted small">Get the latest volunteer commitment forms</p>
                                    <button class="btn btn-outline-primary">Download PDF</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-upload fa-3x text-success mb-3"></i>
                                    <h6>Upload Forms</h6>
                                    <p class="text-muted small">Submit your completed forms</p>
                                    <input type="file" class="form-control mb-2" accept=".pdf">
                                    <button class="btn btn-success">Upload</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6>Submit Prayer Request or Suggestion</h6>
                        <form class="row g-3">
                            <div class="col-md-4">
                                <select class="form-select">
                                    <option>Prayer Request</option>
                                    <option>Suggestion</option>
                                    <option>Feedback</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <textarea class="form-control" rows="3" placeholder="Your message..."></textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Other content sections -->
            <div class="content-section" id="schedule-content">
                <div class="dashboard-card">
                    <h5><i class="fas fa-calendar-alt me-2"></i>My Schedule</h5>
                    <p class="text-muted">Your upcoming volunteer commitments</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Schedule management feature coming soon!
                    </div>
                </div>
            </div>

            <div class="content-section" id="messages-content">
                <div class="dashboard-card">
                    <h5><i class="fas fa-comments me-2"></i>Messages</h5>
                    <p class="text-muted">Communication center</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Messaging system coming soon!
                    </div>
                </div>
            </div>

            <div class="content-section" id="resources-content">
                <div class="dashboard-card">
                    <h5><i class="fas fa-book me-2"></i>Resources</h5>
                    <p class="text-muted">Volunteer handbooks and training materials</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Resource library coming soon!
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="login-page" class="login-container" style="display: flex;">
        <form id="signup-form" style="display:none; margin-top:2rem;">
            <h2>Volunteer Signup</h2>
            <div class="form-group">
                <label for="signup-name">Full Name:</label>
                <input type="text" class="form-control" id="signup-name" required>
            </div>
            <div class="form-group">
                <label for="signup-email">Email:</label>
                <input type="email" class="form-control" id="signup-email" required>
            </div>
            <div class="form-group">
                <label for="signup-phone">Phone:</label>
                <input type="text" class="form-control" id="signup-phone">
            </div>
            <div class="form-group">
                <label for="signup-password">Password:</label>
                <input type="password" class="form-control" id="signup-password" required>
            </div>
            <button type="submit" class="btn btn-success">Sign Up</button>
            <button type="button" class="btn btn-link" id="cancel-signup">Cancel</button>
            <div id="signup-alerts" class="mt-2"></div>
        </form>
        <form id="otp-form" style="display:none; margin-top:2rem;">
            <h2>Admin OTP Verification</h2>
            <div class="form-group">
                <label for="otp-email">Volunteer Email:</label>
                <input type="email" class="form-control" id="otp-email" required>
            </div>
            <div class="form-group">
                <label for="otp-code">OTP Code:</label>
                <input type="text" class="form-control" id="otp-code" required>
            </div>
            <button type="submit" class="btn btn-primary">Verify</button>
            <button type="button" class="btn btn-link" id="cancel-otp">Cancel</button>
            <div id="otp-alerts" class="mt-2"></div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Check for existing session on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedUser = sessionStorage.getItem('volunteerUser');
            if (savedUser) {
                try {
                    const user = JSON.parse(savedUser);
                    // Verify the session is still valid
                    fetch('volunteers_api.php', {
                        method: 'POST',
                        body: new URLSearchParams({action: 'check_session'})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.valid) {
                            showDashboard(user);
                        } else {
                            clearUserSession();
                            showLogin();
                        }
                    })
                    .catch(() => {
                        // If there's an error, still try to show dashboard with saved user
                        showDashboard(user);
                    });
                } catch (e) {
                    console.error('Error parsing saved user:', e);
                    clearUserSession();
                    showLogin();
                }
            } else {
                showLogin();
            }
        });

        // Portal State Management
        let currentUser = null;
        let userDemo = {
            name: 'John Volunteer',
            email: 'volunteer@cefc.org',
            role: 'Active Volunteer',
            avatar: 'JV',
            phone: '+1 (555) 123-4567',
            join_date: 'January 2022'
        };

        // Section switching
        function showSection(section) {
            document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
            const el = document.getElementById(section + '-content');
            if (el) el.classList.add('active');
            document.getElementById('page-title').textContent = {
                dashboard: 'Dashboard',
                profile: 'My Profile',
                volunteer: 'Volunteer Forms',
                schedule: 'My Schedule',
                messages: 'Messages',
                resources: 'Resources'
            }[section] || '';
            document.querySelectorAll('.nav-link').forEach(l=>l.classList.remove('active'));
            document.querySelectorAll('.nav-link[data-section="'+section+'"], .nav-link[href="#"]:not([data-section])').forEach(l=>l.classList.add('active'));
        }
        document.querySelectorAll('.nav-link[data-section]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                showSection(this.getAttribute('data-section'));
            });
        });

        // Sidebar mobile toggle
        window.toggleSidebar = function() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('overlay').style.display = document.getElementById('sidebar').classList.contains('show') ? 'block' : 'none';
        };
        document.getElementById('overlay').onclick = function() {
            document.getElementById('sidebar').classList.remove('show');
            this.style.display = 'none';
        };

        // Login logic
        function saveUserSession(user) {
            sessionStorage.setItem('volunteerUser', JSON.stringify(user));
        }

        function clearUserSession() {
            sessionStorage.removeItem('volunteerUser');
        }

        function showLogin() {
            document.getElementById('login-page').style.display = 'flex';
            document.getElementById('dashboard-page').style.display = 'none';
            // Reset forms
            document.getElementById('login-form').reset();
            document.getElementById('signup-form').style.display = 'none';
            document.getElementById('otp-form').style.display = 'none';
            document.getElementById('login-form').style.display = 'block';
        }

        function showDashboard(user) {
            currentUser = user;
            saveUserSession(user);
            document.getElementById('login-page').style.display = 'none';
            document.getElementById('dashboard-page').style.display = 'block';
            // Fill profile
            document.getElementById('user-name').textContent = user.name;
            document.getElementById('user-role').textContent = user.role;
            document.getElementById('user-avatar').textContent = user.avatar;
            document.querySelectorAll('.profile-avatar').forEach(a=>a.textContent=user.avatar);
            document.querySelectorAll('#profile-content h3').forEach(h=>h.textContent=user.name);
            document.querySelectorAll('#profile-content input[type=email]').forEach(i=>i.value=user.email);
            document.querySelectorAll('#profile-content input[type=text]').forEach(i=>{
                if(i.previousElementSibling && i.previousElementSibling.textContent.includes('Full Name')) i.value=user.name;
                if(i.previousElementSibling && i.previousElementSibling.textContent.includes('Phone')) i.value=user.phone || '';
                if(i.previousElementSibling && i.previousElementSibling.textContent.includes('Join Date')) i.value=user.join_date || new Date().toLocaleDateString();
            });
        }
        function logout() {
            fetch('volunteers_api.php', {method:'POST',body:new URLSearchParams({action:'logout'})})
                .then(()=>{
                    currentUser = null;
                    clearUserSession();
                    showLogin();
                });
        }
        window.logout = logout;

        // Toggle between login, signup, and OTP forms
        const loginPage = document.getElementById('login-page');
        const loginForm = document.getElementById('login-form');
        const signupForm = document.getElementById('signup-form');
        const otpForm = document.getElementById('otp-form');
        // Only attach signup link event once, and remove inline onclick from HTML
        document.querySelectorAll('#show-signup-link').forEach(function(link){
            link.onclick = function(e){
                e.preventDefault();
                loginForm.style.display = 'none';
                signupForm.style.display = 'block';
                otpForm.style.display = 'none';
            };
        });
        document.getElementById('cancel-signup').onclick = function(){
            signupForm.reset();
            signupForm.style.display = 'none';
            loginForm.style.display = 'block';
            otpForm.style.display = 'none';
            document.getElementById('signup-alerts').innerHTML = '';
        };
        document.getElementById('show-otp-link').onclick = function(e){
            e.preventDefault();
            loginForm.style.display = 'none';
            signupForm.style.display = 'none';
            otpForm.style.display = 'block';
        };
        document.getElementById('cancel-otp').onclick = function(){
            otpForm.reset();
            otpForm.style.display = 'none';
            loginForm.style.display = 'block';
            signupForm.style.display = 'none';
            document.getElementById('otp-alerts').innerHTML = '';
        };

        // Signup form AJAX
        signupForm.onsubmit = function(e){
            e.preventDefault();
            let name = document.getElementById('signup-name').value.trim();
            let email = document.getElementById('signup-email').value.trim();
            let phone = document.getElementById('signup-phone').value.trim();
            let ministry = document.getElementById('signup-ministry').value.trim();
            let password = document.getElementById('signup-password').value;
            if(!name||!email||!password||!ministry) return;
            let fd = new URLSearchParams();
            fd.append('action','signup');
            fd.append('name',name);
            fd.append('email',email);
            fd.append('phone',phone);
            fd.append('ministry',ministry);
            fd.append('password',password);
            fetch('volunteers_api.php',{method:'POST',body:fd})
                .then(r=>r.json()).then(data=>{
                    let alerts = document.getElementById('signup-alerts');
                    alerts.innerHTML = '<div class="alert '+(data.success?'alert-success':'alert-danger')+'">'+data.message+'</div>';
                    if(data.success){
                        signupForm.reset();
                        setTimeout(()=>{
                            signupForm.style.display='none';
                            loginForm.style.display='block';
                            alerts.innerHTML='';
                        },2000);
                    }
                });
        };

        // OTP verify form AJAX
        otpForm.onsubmit = function(e){
            e.preventDefault();
            let email = document.getElementById('otp-email').value.trim();
            let otp = document.getElementById('otp-code').value.trim();
            if(!email||!otp) return;
            let fd = new URLSearchParams();
            fd.append('action','verify_otp');
            fd.append('email',email);
            fd.append('otp',otp);
            fetch('volunteers_api.php',{method:'POST',body:fd})
                .then(r=>r.json()).then(data=>{
                    let alerts = document.getElementById('otp-alerts');
                    alerts.innerHTML = '<div class="alert '+(data.success?'alert-success':'alert-danger')+'">'+data.message+'</div>';
                    if(data.success){
                        otpForm.reset();
                        setTimeout(()=>{
                            otpForm.style.display='none';
                            loginForm.style.display='block';
                            alerts.innerHTML='';
                        },2000);
                    }
                });
        };

        // Login form
        loginForm.onsubmit = function(e) {
            e.preventDefault();
            let email = document.getElementById('email').value.trim();
            let password = document.getElementById('password').value;
            fetch('volunteers_api.php', {method:'POST',body:new URLSearchParams({action:'login',email,password})})
                .then(r=>r.json()).then(data=>{
                    if(data.success) { showDashboard(data.user); showSection('dashboard'); }
                    else document.getElementById('login-alerts').innerHTML = '<div class="alert alert-danger">'+data.message+'</div>';
                });
        };
        // Demo: auto-login if already in session (not persistent in this demo)
        showLogin();

        // Download form
        document.querySelectorAll('button, a').forEach(btn=>{
            if(btn.textContent.includes('Download PDF')){
                btn.onclick = function(e){
                    e.preventDefault();
                    window.open('./assets/commitment_form.pdf','_blank');
                };
            }
        });

        // Upload form
        document.querySelectorAll('input[type=file]').forEach(inp=>{
            let btn = inp.closest('.card,form').querySelector('button.btn-success');
            if(btn){
                btn.onclick = function(e){
                    e.preventDefault();
                    let file = inp.files[0];
                    if(!file) return alert('Please select a file.');
                    let formData = new FormData();
                    formData.append('action','upload_commitment');
                    formData.append('file',file);
                    fetch('volunteers_api.php', {method:'POST',body:formData})
                        .then(r=>r.json()).then(data=>{
                            let msg = document.createElement('div');
                            msg.className = 'alert ' + (data.success ? 'alert-success':'alert-danger');
                            msg.innerHTML = (data.success?'<i class="fas fa-check-circle me-2"></i>':'<i class="fas fa-exclamation-circle me-2"></i>') + data.message;
                            inp.closest('.card, .dashboard-card').prepend(msg);
                            setTimeout(()=>msg.remove(),4000);
                        });
                };
            }
        });

        // Prayer/suggestion form
        document.querySelectorAll('#volunteer-content form, .dashboard-card form').forEach(form=>{
            if(form.querySelector('textarea') && form.querySelector('button.btn-primary')){
                form.onsubmit = function(e){
                    e.preventDefault();
                    let sel = form.querySelector('select');
                    let txt = form.querySelector('textarea');
                    let type = sel ? (sel.value==='Prayer Request'?'prayer':'suggestion') : 'suggestion';
                    let msg = txt.value.trim();
                    if(!msg) return;
                    let fd = new URLSearchParams();
                    fd.append('action','submit_message');
                    fd.append('msg_type',type);
                    fd.append('message',msg);
                    fetch('volunteers_api.php', {method:'POST',body:fd})
                        .then(r=>r.json()).then(data=>{
                            let alert = document.createElement('div');
                            alert.className = 'alert ' + (data.success ? 'alert-success':'alert-danger');
                            alert.innerHTML = (data.success?'<i class="fas fa-check-circle me-2"></i>':'<i class="fas fa-exclamation-circle me-2"></i>') + data.message;
                            form.prepend(alert);
                            setTimeout(()=>alert.remove(),4000);
                            if(data.success) txt.value = '';
                        });
                };
            }
        });

        // Register/forgot password (demo only)
        window.showRegister = function(){

        }
        window.showForgotPassword = function(){
            alert('Password reset coming soon!');
        }
    </script>