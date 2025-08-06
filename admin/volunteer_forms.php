<?php
// Admin: List all uploaded volunteer commitment forms
$uploadDir = __DIR__ . '/uploads/volunteers/';
$files = [];
if (is_dir($uploadDir)) {
    foreach (glob($uploadDir . '*.pdf') as $file) {
        $files[] = [
            'name' => basename($file),
            'path' => '/uploads/volunteers/' . basename($file),
            'size' => filesize($file),
            'uploaded' => date('Y-m-d H:i', filemtime($file))
        ];
    }
}
?>
<?php
// Volunteer forms listing logic
$uploadDir = __DIR__ . '/../uploads/volunteers/';
$files = [];
if (is_dir($uploadDir)) {
    foreach (glob($uploadDir . '*.pdf') as $file) {
        $files[] = [
            'name' => basename($file),
            'path' => '/uploads/volunteers/' . basename($file),
            'size' => filesize($file),
            'uploaded' => date('Y-m-d H:i', filemtime($file))
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Commitment Forms - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            --border-radius: 16px;
            --box-shadow: 0 4px 25px rgba(76, 29, 149, 0.08);
            --transition: all 0.4s ease;
        }
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: var(--off-white);
            color: var(--text-dark);
        }
        .sidebar {
            position: fixed;
            left: 0; top: 0; bottom: 0;
            width: 280px;
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            color: var(--white);
            box-shadow: var(--box-shadow);
            z-index: 1000;
            padding: 1.5rem 1rem 1rem 1rem;
            border-radius: 0 32px 32px 0;
        }
        .sidebar-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .sidebar-header i {
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }
        .sidebar-menu .nav-link {
            color: var(--white);
            font-weight: 500;
            margin-bottom: 0.5rem;
            border-radius: 8px;
            transition: var(--transition);
        }
        .sidebar-menu .nav-link.active, .sidebar-menu .nav-link:hover {
            background: var(--light-purple);
            color: var(--primary-purple);
        }
        .main-content {
            margin-left: 280px;
            padding: 2.5rem 2rem 2rem 2rem;
        }
        .navbar {
            border-radius: 0 0 16px 16px;
            margin-bottom: 2rem;
        }
        .content-area {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2rem 2rem 1.5rem 2rem;
        }
        .table {
            background: var(--white);
        }
        @media (max-width: 991px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; padding: 1rem; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-church"></i>
            <h3>Church Admin</h3>
            <small class="text-white-50">Management System</small>
        </div>
        <nav class="sidebar-menu">
            <a href="index.php" class="nav-link"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
            <a href="#" class="nav-link"><i class="fas fa-microphone"></i> <span>Sermons</span></a>
            <a href="#" class="nav-link"><i class="fas fa-users"></i> <span>Members</span></a>
            <a href="#" class="nav-link"><i class="fas fa-calendar-alt"></i> <span>Events</span></a>
            <a href="#" class="nav-link"><i class="fas fa-hand-holding-heart"></i> <span>Donations</span></a>
            <a href="#" class="nav-link"><i class="fas fa-chart-bar"></i> <span>Reports</span></a>
            <a href="volunteer_forms.php" class="nav-link active"><i class="fas fa-file-pdf"></i> <span>Volunteer Forms</span></a>
            <a href="#" class="nav-link"><i class="fas fa-cog"></i> <span>Settings</span></a>
            <a href="?action=logout" class="nav-link text-warning" onclick="return confirm('Are you sure you want to logout?')"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
        </nav>
    </div>
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple)); box-shadow: var(--box-shadow); z-index: 1100;">
            <div class="container-fluid">
                <button class="btn btn-outline-primary d-lg-none me-2" type="button" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="navbar-brand d-flex align-items-center gap-2" href="/admin/index.php">
                    <img src="../assets/images/logo.png" alt="Christ Ekklesians Fellowship Chapel Logo" class="navbar-logo me-2" style="height: 40px; width: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                    <span style="font-family: 'Playfair Display', serif; font-weight: 700; font-size: 1.25rem; letter-spacing: 0.5px;">Christ Ekklesia Fellowship Chapel</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="adminNavbar">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link active" href="volunteer_forms.php">Volunteer Forms</a></li>
                        <li class="nav-item"><a class="nav-link" href="?action=logout" onclick="return confirm('Are you sure you want to logout?')">Logout</a></li>
                    </ul>
                    <span class="navbar-text ms-lg-4 d-none d-lg-inline" style="color: var(--accent-gold); font-weight: 500;">
                        <i class="fas fa-calendar me-2"></i><?= date('l, F j, Y') ?>
                    </span>
                </div>
            </div>
        </nav>
        <div style="height: 64px;"></div> <!-- Spacer for fixed navbar -->
        <!-- Content Area -->
        <div class="content-area container-fluid px-0 px-md-3">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-8">
                    <h2 class="mb-4" style="font-family: 'Playfair Display', serif; font-weight: 700; color: var(--primary-purple); letter-spacing: 0.5px;">Volunteer Commitment Forms</h2>
                    <?php if (!empty($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= htmlspecialchars($_SESSION['success']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-header" style="background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple)); color: #fff; font-weight: 600; font-size: 1.15rem; display: flex; align-items: center; gap: 0.5em;">
                            <i class="fas fa-file-pdf"></i>
                            <span>Uploaded Volunteer Commitment Forms</span>
                        </div>
                        <div class="card-body">
                            <?php if (empty($files)): ?>
                                <div class="alert alert-info alert-dismissible fade show mb-0" role="alert">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No forms uploaded yet.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle rounded" style="overflow: hidden;">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">File Name</th>
                                            <th scope="col">Uploaded</th>
                                            <th scope="col">Size</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($files as $file): ?>
                                        <tr>
                                            <td><a href="<?= htmlspecialchars($file['path']) ?>" target="_blank" class="fw-semibold text-decoration-none"><i class="fas fa-file-pdf text-danger me-2"></i><?= htmlspecialchars($file['name']) ?></a></td>
                                            <td><?= htmlspecialchars($file['uploaded']) ?></td>
                                            <td><?= round($file['size'] / 1024, 2) ?> KB</td>
                                            <td><a href="<?= htmlspecialchars($file['path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i> Download</a></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif; ?>
                            <a href="index.php" class="btn btn-secondary mt-3">Back to Admin Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
