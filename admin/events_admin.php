<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php'); exit;
}
// DB config
$host = 'localhost';
$dbname = 'kazrxdvk_church_management';
$username = 'kazrxdvk_vincent';
$password = '@Admin@2025';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
// Handle add/edit/delete POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $location = $_POST['location'] ?? '';
    $description = $_POST['description'] ?? '';
    $bannerPath = '';
    // Handle banner upload
    if (isset($_FILES['banner']) && $_FILES['banner']['error'] !== UPLOAD_ERR_NO_FILE) {
        $fileTmp = $_FILES['banner']['tmp_name'];
        $fileName = basename($_FILES['banner']['name']);
        $fileSize = $_FILES['banner']['size'];
        $fileType = mime_content_type($fileTmp);
        $allowedTypes = ['image/jpeg','image/png','image/gif'];
        if (in_array($fileType, $allowedTypes) && $fileSize <= 2*1024*1024) {
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $newName = uniqid('event_', true) . '.' . $ext;
            $dest = __DIR__ . '/../uploads/' . $newName;
            if (move_uploaded_file($fileTmp, $dest)) {
                $bannerPath = 'uploads/' . $newName;
            }
        }
    }
    if (isset($_POST['add_event'])) {
        $stmt = $pdo->prepare("INSERT INTO events (title, date, time, location, description, banner) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $date, $time, $location, $description, $bannerPath]);
        $_SESSION['success'] = 'Event added!';
        header('Location: events_admin.php'); exit;
    }
    if (isset($_POST['edit_event']) && isset($_POST['event_id'])) {
        $eventId = $_POST['event_id'];
        if (!$bannerPath) {
            $stmt = $pdo->prepare("SELECT banner FROM events WHERE id = ?");
            $stmt->execute([$eventId]);
            $row = $stmt->fetch();
            $bannerPath = $row ? $row['banner'] : '';
        }
        $stmt = $pdo->prepare("UPDATE events SET title=?, date=?, time=?, location=?, description=?, banner=? WHERE id=?");
        $stmt->execute([$title, $date, $time, $location, $description, $bannerPath, $eventId]);
        $_SESSION['success'] = 'Event updated!';
        header('Location: events_admin.php'); exit;
    }
    if (isset($_POST['delete_event']) && isset($_POST['event_id'])) {
        $eventId = $_POST['event_id'];
        $stmt = $pdo->prepare("DELETE FROM events WHERE id=?");
        $stmt->execute([$eventId]);
        $_SESSION['success'] = 'Event deleted!';
        header('Location: events_admin.php'); exit;
    }
}
// Fetch all events (future and past)
$stmt = $pdo->query("SELECT * FROM events ORDER BY date DESC");
$events = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church Admin - Events</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-purple: #60379e;
            --secondary-purple: #7e4ba8;
            --light-purple: #f3e8ff;
            --accent-purple: #8b5cf6;
            --accent-gold: #f59e0b;
            --dark-purple: #312e81;
            --box-shadow: 0 4px 24px rgba(76, 29, 149, 0.12);
        }
        body { font-family: 'Inter', sans-serif; background: var(--light-purple); }
        .navbar-logo { height: 40px; width: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: 280px; background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple)); color: #fff; z-index: 1050; box-shadow: var(--box-shadow); transition: transform 0.3s; }
        .sidebar-header { padding: 2rem 1.5rem 1rem; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.08); }
        .sidebar-header h3 { font-family: 'Playfair Display', serif; font-weight: 700; margin-bottom: 0.2em; }
        .sidebar-header i { font-size: 2rem; color: var(--accent-gold); margin-bottom: 0.5em; }
        .sidebar-menu { display: flex; flex-direction: column; gap: 0.5rem; padding: 2rem 1.5rem; }
        .sidebar-menu .nav-link { color: #fff; font-weight: 500; border-radius: 8px; padding: 0.75rem 1rem; font-size: 1.05rem; transition: background 0.2s; }
        .sidebar-menu .nav-link:hover, .sidebar-menu .nav-link.active { background: var(--accent-purple); color: #fff; }
        .sidebar-menu .nav-link i { margin-right: 0.75em; }
        .main-content { margin-left: 280px; min-height: 100vh; background: var(--light-purple); transition: margin 0.3s; }
        .navbar { background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple)); box-shadow: var(--box-shadow); }
        .content-area { padding: 2.5rem 2rem 2rem 2rem; }
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
        .navbar-logo { height: 40px; width: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="../assets/images/logo.png" alt="Christ Ekklesia Fellowship Chapel Logo" class="navbar-logo me-2">
            <h3>Church Admin</h3>
            <small class="text-white-50">Management System</small>
        </div>
        <nav class="sidebar-menu">
            <a href="index.php" class="nav-link"><i class="fas fa-microphone"></i><span>Sermons</span></a>
            <a href="#" class="nav-link"><i class="fas fa-users"></i><span>Members</span></a>
            <a href="events_admin.php" class="nav-link active"><i class="fas fa-calendar-alt"></i><span>Events</span></a>
            <a href="#" class="nav-link"><i class="fas fa-hand-holding-heart"></i><span>Donations</span></a>
            <a href="#" class="nav-link"><i class="fas fa-chart-bar"></i><span>Reports</span></a>
            <a href="volunteer_forms.php" class="nav-link"><i class="fas fa-file-pdf"></i><span>Volunteer Forms</span></a>
            <a href="#" class="nav-link"><i class="fas fa-cog"></i><span>Settings</span></a>
            <a href="?action=logout" class="nav-link text-warning" onclick="return confirm('Are you sure you want to logout?')"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
        </nav>
    </div>
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="left:280px; width:calc(100% - 280px); z-index:1100;">
            <div class="container-fluid">
                <button class="btn btn-outline-primary d-lg-none me-2" type="button" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="navbar-brand d-flex align-items-center gap-2" href="/admin/index.php">
                    <img src="../assets/images/logo.png" alt="Christ Ekklesians Fellowship Chapel Logo" class="navbar-logo me-2">
                    <span style="font-family: 'Playfair Display', serif; font-weight: 700; font-size: 1.25rem; letter-spacing: 0.5px;">Christ Ekklesia Fellowship Chapel</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="adminNavbar">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="volunteer_forms.php">Volunteer Forms</a></li>
                        <li class="nav-item"><a class="nav-link" href="?action=logout" onclick="return confirm('Are you sure you want to logout?')">Logout</a></li>
                    </ul>
                    <span class="navbar-text ms-lg-4 d-none d-lg-inline" style="color: var(--accent-gold); font-weight: 500;">
                        <i class="fas fa-calendar me-2"></i><?= date('l, F j, Y') ?>
                    </span>
                </div>
            </div>
        </nav>
        <div style="height: 64px;"></div>
        <!-- Content Area -->
        <div class="content-area">
            <h2 class="mb-4">Manage Events</h2>
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success"> <?= $_SESSION['success']; unset($_SESSION['success']); ?> </div>
            <?php endif; ?>
            <!-- Event Form -->
    <form method="post" enctype="multipart/form-data" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4"><input required type="text" name="title" class="form-control" placeholder="Title"></div>
            <div class="col-md-2"><input required type="date" name="date" class="form-control"></div>
            <div class="col-md-2"><input required type="time" name="time" class="form-control"></div>
            <div class="col-md-4"><input required type="text" name="location" class="form-control" placeholder="Location"></div>
            <div class="col-12"><textarea name="description" class="form-control" placeholder="Description"></textarea></div>
            <div class="col-md-4"><input type="file" name="banner" class="form-control"></div>
            <div class="col-md-2"><button type="submit" name="add_event" class="btn btn-primary w-100"><i class="fas fa-plus"></i> Add Event</button></div>
        </div>
    </form>
    <!-- Events Table -->
    <div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Title</th><th>Date</th><th>Time</th><th>Location</th><th>Description</th><th>Banner</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($events as $event): ?>
            <tr>
                <form method="post" enctype="multipart/form-data">
                <td><input type="text" name="title" value="<?= htmlspecialchars($event['title']) ?>" class="form-control" required></td>
                <td><input type="date" name="date" value="<?= $event['date'] ?>" class="form-control" required></td>
                <td><input type="time" name="time" value="<?= $event['time'] ?>" class="form-control" required></td>
                <td><input type="text" name="location" value="<?= htmlspecialchars($event['location']) ?>" class="form-control" required></td>
                <td><textarea name="description" class="form-control"><?= htmlspecialchars($event['description']) ?></textarea></td>
                <td><?php if ($event['banner']): ?><img src="../<?= $event['banner'] ?>" style="max-width:80px;max-height:60px;object-fit:cover;"/><?php endif; ?><input type="file" name="banner" class="form-control mt-1"></td>
                <td>
                    <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                    <button type="submit" name="edit_event" class="btn btn-sm btn-success mb-1"><i class="fas fa-save"></i></button>
                    <button type="submit" name="delete_event" class="btn btn-sm btn-danger" onclick="return confirm('Delete this event?')"><i class="fas fa-trash"></i></button>
                </td>
                </form>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
</body>
</html>
