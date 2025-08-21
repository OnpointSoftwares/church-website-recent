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
            'timestamp' => filemtime($file),
            'uploaded' => date('Y-m-d H:i', filemtime($file))
        ];
    }
}
// Sort newest first
usort($files, function($a, $b) { return ($b['timestamp'] ?? 0) <=> ($a['timestamp'] ?? 0); });
// Count
$totalFiles = count($files);
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
    <link rel="stylesheet" href="../style.css">
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
            font-family: 'Inter', sans-serif;
            background: var(--off-white);
            color: var(--text-dark);
            line-height: 1.6;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            color: var(--text-dark);
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(180deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
            box-shadow: var(--box-shadow);
            z-index: 1000;
            transform: translateX(-100%);
            transition: var(--transition);
        }
        .sidebar.active { transform: translateX(0); }
        .sidebar-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .sidebar-header i {
            font-size: 2.2rem;
        }
        .sidebar-menu { padding: 1rem 0; }
        .sidebar-menu .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem 1.5rem;
            transition: var(--transition);
            border: none;
            border-left: 3px solid transparent;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .sidebar-menu .nav-link:hover,
        .sidebar-menu .nav-link.active {
            color: var(--accent-gold);
            background: rgba(255, 255, 255, 0.1);
            border-left-color: var(--accent-gold);
            backdrop-filter: blur(10px);
        }
        .sidebar-menu .nav-link i { width: 20px; margin-right: 12px; font-size: 1.1rem; }
        .main-content {
            margin-left: 280px;
            padding: 2.5rem 2rem 2rem 2rem;
        }
        .navbar {
            background: var(--white) !important;
            box-shadow: var(--box-shadow);
            border-bottom: 1px solid rgba(96, 55, 158, 0.1);
            padding: 1rem 2rem;
        }
        /* Page banner to match site vibrancy */
        .page-banner {
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            color: #fff;
            padding: 2rem 0;
            box-shadow: 0 6px 20px rgba(96,55,158,0.15);
        }
        .page-banner .lead { opacity: 0.9; }
        .content-area {
            padding: 2rem;
            max-width: 100%;
            overflow-x: hidden;
        }
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            overflow: hidden;
        }
        .card:hover { box-shadow: 0 8px 35px rgba(76, 29, 149, 0.15); transform: translateY(-2px); }
        .card-header { background: linear-gradient(135deg, var(--primary-purple), var(--accent-purple)); color: var(--white); border: none; padding: 1.5rem; }
        .card-header h5 { margin: 0; color: var(--white); display: flex; align-items: center; }
        .card-header i { margin-right: 0.5rem; color: var(--accent-gold); }
        .form-control { border: 2px solid rgba(96, 55, 158, 0.1); border-radius: 12px; padding: 0.8rem 1rem; transition: var(--transition); }
        .form-control:focus { border-color: var(--accent-purple); box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1); }
        .btn-primary { background: linear-gradient(135deg, var(--primary-purple), var(--accent-purple)); border: none; border-radius: 12px; padding: 0.8rem 1.5rem; font-weight: 600; transition: var(--transition); }
        .btn-primary:hover { background: linear-gradient(135deg, var(--accent-purple), var(--primary-purple)); transform: translateY(-2px); box-shadow: 0 5px 20px rgba(96, 55, 158, 0.3); }
        .table { border-radius: 12px; overflow: hidden; }
        .table th { background: var(--light-purple); border: none; font-weight: 600; color: var(--text-dark); padding: 1rem; }
        .table td { border-color: rgba(96, 55, 158, 0.1); padding: 1rem; vertical-align: middle; }
        .badge { padding: 0.5rem 1rem; border-radius: 25px; font-weight: 500; }
        .alert { border: none; border-radius: 12px; padding: 1rem 1.5rem; }
        .alert-success { background: linear-gradient(135deg, #10b981, #059669); color: var(--white); }
        .btn-outline-primary { color: var(--primary-purple); border: 2px solid var(--primary-purple); border-radius: 8px; }
        .btn-outline-primary:hover { background: var(--primary-purple); border-color: var(--primary-purple); }
        .btn-outline-danger { color: #ef4444; border: 2px solid #ef4444; border-radius: 8px; }
        .btn-outline-danger:hover { background: #ef4444; border-color: #ef4444; }
        .modal-content { border: none; border-radius: var(--border-radius); box-shadow: 0 20px 60px rgba(76, 29, 149, 0.2); }
        .modal-header { background: linear-gradient(135deg, var(--primary-purple), var(--accent-purple)); color: var(--white); border: none; border-radius: var(--border-radius) var(--border-radius) 0 0; }
        /* Sticky table header and purple accent striping */
        thead.sticky-header th {
            position: sticky;
            top: 0;
            z-index: 5;
            background: #ffffff;
            box-shadow: 0 2px 0 rgba(0,0,0,0.03);
        }
        .table.table-striped tbody tr:nth-of-type(odd) { background-color: #fbf7ff; }
        .table thead th { color: var(--primary-purple); font-weight: 600; }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                transform: translateX(-100%);
            }
            .main-content { margin-left: 0; min-height: 100vh; transition: var(--transition); }
            .main-content.sidebar-open { margin-left: 280px; }
            .content-area { padding: 1rem; }
            .table-responsive { border-radius: 12px; }
        }
        @media (min-width: 992px) {
            .sidebar { transform: translateX(0); }
            .main-content { margin-left: 280px; }
            .navbar .btn[data-bs-toggle="offcanvas"] { display: none; }
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
            <a href="#" class="nav-link">
                <i class="fas fa-microphone"></i>
                <span>Sermons</span>
            </a>
            <a href="#" class="nav-link">
                <i class="fas fa-users"></i>
                <span>Members</span>
            </a>
            <a href="events_admin.php" class="nav-link<?php if (basename($_SERVER['PHP_SELF']) === 'events_admin.php') echo ' active'; ?>">
                <i class="fas fa-calendar-alt"></i>
                <span>Events</span>
            </a>
            <a href="#" class="nav-link">
                <i class="fas fa-hand-holding-heart"></i>
                <span>Donations</span>
            </a>
            <a href="#" class="nav-link">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </a>
            <a href="volunteer_forms.php" class="nav-link active">
                <i class="fas fa-file-pdf"></i>
                <span>Volunteer Forms</span>
            </a>
            <a href="#" class="nav-link">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
            <a href="?action=logout" class="nav-link text-warning" onclick="return confirm('Are you sure you want to logout?')">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
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
        <!-- Page Banner -->
        <section class="page-banner">
            <div class="container-fluid px-0 px-md-3">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10 col-xl-8">
                        <h1 class="h3 mb-1 d-flex align-items-center gap-2">
                            <i class="fas fa-file-shield"></i>
                            Volunteer Commitment Forms
                        </h1>
                        <p class="lead mb-0">Review and manage uploaded documents from volunteers.</p>
                        <nav aria-label="breadcrumb" class="mt-2">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="index.php" class="text-white text-decoration-underline">Admin Dashboard</a></li>
                                <li class="breadcrumb-item active text-white-50" aria-current="page">Volunteer Forms</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
        <!-- Content Area -->
        <div class="content-area container-fluid px-0 px-md-3">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-8">
                    <h2 class="visually-hidden">Volunteer Commitment Forms</h2>
                    <p class="text-muted mt-3 mb-4">Manage uploaded commitment forms. <span class="badge bg-primary"><?= $totalFiles ?> file<?= $totalFiles === 1 ? '' : 's' ?></span></p>
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
                            <div class="row align-items-center mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                        <input type="text" id="formSearch" class="form-control" placeholder="Search by filename...">
                                    </div>
                                </div>
                                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                                    <a href="../volunteers.php" class="btn btn-outline-primary"><i class="fas fa-user-shield me-2"></i>Open Volunteer Portal</a>
                                </div>
                            </div>
                            <?php if (empty($files)): ?>
                                <div class="alert alert-info alert-dismissible fade show mb-0" role="alert">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No forms uploaded yet.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle rounded" id="formsTable" style="overflow: hidden;">
                                    <thead class="table-light sticky-header">
                                        <tr>
                                            <th scope="col">File Name</th>
                                            <th scope="col">Uploaded</th>
                                            <th scope="col">Size</th>
                                            <th scope="col" class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($files as $file): ?>
                                        <tr>
                                            <td><a href="<?= htmlspecialchars($file['path']) ?>" target="_blank" class="fw-semibold text-decoration-none"><i class="fas fa-file-pdf text-danger me-2"></i><?= htmlspecialchars($file['name']) ?></a></td>
                                            <td><?= htmlspecialchars($file['uploaded']) ?></td>
                                            <td><?= round($file['size'] / 1024, 2) ?> KB</td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="<?= htmlspecialchars($file['path']) ?>" target="_blank" class="btn btn-outline-primary" title="Download PDF"><i class="fas fa-download"></i></a>
                                                    <button type="button" class="btn btn-outline-secondary" onclick="copyLink('<?= htmlspecialchars($file['path']) ?>')" title="Copy link"><i class="fas fa-link"></i></button>
                                                </div>
                                            </td>
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

    <!-- Footer Credit -->
    <footer class="py-3 mt-4 text-center">
        <p class="mb-0 text-muted">Crafted by 
            <a href="https://onpointsoft.pythonanywhere.com" target="_blank" rel="noopener" class="text-decoration-underline">Onpoint Softwares Solutions</a>
        </p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile sidebar toggle
        function toggleSidebar(){
            const sb = document.getElementById('sidebar');
            if (!sb) return;
            if (getComputedStyle(sb).display === 'none') {
                sb.style.display = 'block';
                document.body.style.overflow = 'hidden';
            } else {
                sb.style.display = '';
                document.body.style.overflow = '';
            }
        }
        // Search filter
        const formSearch = document.getElementById('formSearch');
        if (formSearch) {
            formSearch.addEventListener('input', function(){
                const q = this.value.toLowerCase();
                document.querySelectorAll('#formsTable tbody tr').forEach(tr => {
                    const name = tr.querySelector('td')?.innerText.toLowerCase() || '';
                    tr.style.display = name.includes(q) ? '' : 'none';
                });
            });
        }
        // Copy link helper
        function copyLink(url){
            navigator.clipboard.writeText(location.origin + url).then(() => {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success py-2 px-3 position-fixed';
                alertDiv.style.right = '1rem';
                alertDiv.style.bottom = '1rem';
                alertDiv.innerHTML = '<i class="fas fa-check-circle me-2"></i>Link copied';
                document.body.appendChild(alertDiv);
                setTimeout(()=>alertDiv.remove(), 1500);
            });
        }
    </script>
</body>
</html>
