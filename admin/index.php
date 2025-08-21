<?php
// Modern Church Admin Dashboard with Database Integration
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'kazrxdvk_church_management';
$username = 'kazrxdvk_vincent';
$password = '@Admin@2025';

// Create database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Create tables if they don't exist
$createTables = "
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS sermons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(500) NOT NULL,
    date DATE NOT NULL,
    speaker VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    phone VARCHAR(50),
    joined_date DATE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    event_time TIME,
    location VARCHAR(255),
    status ENUM('upcoming', 'completed', 'cancelled') DEFAULT 'upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";

$pdo->exec($createTables);

// Create default admin if none exists
$adminCheck = $pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
if ($adminCheck == 0) {
    $defaultPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)")
        ->execute(['admin', $defaultPassword]);
}

// Authentication
if (!isset($_SESSION['admin_logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $stmt = $pdo->prepare("SELECT id, password FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $loginError = 'Invalid credentials';
        }
    }
    
    // Login form
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Church Admin - Login</title>
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
                background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 50%, var(--accent-purple) 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Inter', sans-serif;
                overflow-y: auto;
                position: relative;
            }
            
            body::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
                background-size: 40px 40px;
                animation: float 20s ease-in-out infinite;
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            
            .login-container {
                position: relative;
                z-index: 10;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: var(--border-radius);
                box-shadow: 0 20px 60px rgba(76, 29, 149, 0.2);
                overflow: hidden;
                max-width: 450px;
                width: 100%;
            }
            
            .login-header {
                background: var(--primary-purple);
                padding: 2.25rem 2rem 1.5rem;
                text-align: center;
                position: relative;
                overflow: hidden;
            }
            
            .login-header::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
                transform: rotate(45deg);
                animation: shimmer 3s ease-in-out infinite;
            }
            
            @keyframes shimmer {
                0%, 100% { transform: translateX(-100%) rotate(45deg); }
                50% { transform: translateX(100%) rotate(45deg); }
            }
            
            .login-header .brand-logo {
                height: 56px;
                width: auto;
                border-radius: 10px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.12);
                margin-bottom: 0.75rem;
                position: relative;
                z-index: 2;
            }
            
            .login-header h2 {
                font-family: 'Playfair Display', serif;
                font-weight: 600;
                color: var(--white);
                margin: 0 0 0.5rem;
                font-size: 2rem;
                position: relative;
                z-index: 2;
            }
            
            .login-header p {
                color: rgba(255, 255, 255, 0.8);
                margin: 0;
                position: relative;
                z-index: 2;
            }
            
            .login-form { padding: 2rem; }
            
            .form-floating { position: relative; margin-bottom: 1.25rem; }
            
            .form-control {
                border: 2px solid rgba(96, 55, 158, 0.1);
                border-radius: 12px;
                padding: 1rem 1rem 1rem 3rem;
                font-size: 1rem;
                transition: var(--transition);
                background: rgba(248, 250, 252, 0.8);
            }
            
            .form-control:focus {
                border-color: var(--accent-purple);
                box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
                background: var(--white);
            }
            
            .input-icon {
                position: absolute;
                left: 1rem;
                top: 50%;
                transform: translateY(-50%);
                color: var(--text-light);
                z-index: 5;
                transition: var(--transition);
            }
            .toggle-password {
                position: absolute;
                right: 0.75rem;
                top: 50%;
                transform: translateY(-50%);
                background: transparent;
                border: none;
                color: var(--text-light);
                z-index: 5;
            }
            
            .form-control:focus + .input-icon {
                color: var(--accent-purple);
            }
            
            .btn-login {
                background: linear-gradient(135deg, var(--primary-purple), var(--accent-purple));
                border: none;
                border-radius: 12px;
                padding: 0.8rem 2rem;
                font-weight: 600;
                font-size: 1.1rem;
                width: 100%;
                transition: var(--transition);
                box-shadow: 0 4px 15px rgba(96, 55, 158, 0.3);
            }
            
            .btn-login:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(96, 55, 158, 0.4);
                background: linear-gradient(135deg, var(--accent-purple), var(--primary-purple));
            }
            
            .alert {
                border-radius: 12px;
                border: none;
                padding: 1rem;
                margin-bottom: 1.5rem;
            }
            
            .alert-danger {
                background: linear-gradient(135deg, #ef4444, #dc2626);
                color: white;
            }
            
            .login-meta { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
            .login-meta a { color: var(--accent-purple); text-decoration: none; }
            .login-meta a:hover { text-decoration: underline; }
            @media (max-width: 576px) {
                .login-container {
                    margin: 1rem;
                    max-width: calc(100% - 2rem);
                }
                .login-header { padding: 1.75rem 1.25rem 1.25rem; }
                .login-form { padding: 1.5rem; }
            }
        </style>
    </head>
    <body>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-9 col-md-7 col-lg-5 col-xl-4">
                    <div class="login-container">
                        <div class="login-header">
                            <img src="../assets/images/logo.png" alt="Christ Ekklesia Fellowship Chapel Logo" class="brand-logo">
                            <h2>Church Admin</h2>
                            <p>Sign in to your account</p>
                        </div>
                        <div class="login-form">
                            <?php if (isset($loginError)): ?>
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <?= htmlspecialchars($loginError) ?>
                                </div>
                            <?php endif; ?>
                            
                            <form method="post" novalidate>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="username" name="username" required>
                                    <i class="fas fa-user input-icon"></i>
                                    <label for="username">Username</label>
                                </div>
                                
                                <div class="form-floating">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <i class="fas fa-lock input-icon"></i>
                                    <button type="button" class="toggle-password" aria-label="Show password" onclick="togglePw()"><i class="far fa-eye"></i></button>
                                    <label for="password">Password</label>
                                </div>
                                <div class="login-meta">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="remember">
                                        <label class="form-check-label" for="remember">Remember me</label>
                                    </div>
                                    <a href="#">Forgot password?</a>
                                </div>
                                
                                <button type="submit" name="login" class="btn btn-primary btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Sign In
                                </button>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
        <script>
            function togglePw() {
                const input = document.getElementById('password');
                const btn = document.querySelector('.toggle-password i');
                const isPw = input.getAttribute('type') === 'password';
                input.setAttribute('type', isPw ? 'text' : 'password');
                btn.classList.toggle('fa-eye');
                btn.classList.toggle('fa-eye-slash');
            }
        </script>
    </body>
    </html>
    <?php
    exit;
}

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add sermon
    if (isset($_POST['add_sermon'])) {
        // Handle file upload
        $thumbnailPath = '';
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['thumbnail']['tmp_name'];
            $fileName = basename($_FILES['thumbnail']['name']);
            $fileSize = $_FILES['thumbnail']['size'];
            $fileType = mime_content_type($fileTmp);
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($fileType, $allowedTypes) && $fileSize <= 2*1024*1024) {
                $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                $newName = uniqid('sermon_', true) . '.' . $ext;
                $dest = __DIR__ . '/../uploads/' . $newName;
                if (move_uploaded_file($fileTmp, $dest)) {
                    $thumbnailPath = './uploads/' . $newName;
                }
            }
        }
        $stmt = $pdo->prepare("INSERT INTO sermons (title, date, speaker, content, thumbnail, youtube) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['title'],
            $_POST['date'],
            $_POST['speaker'],
            $_POST['content'],
            $thumbnailPath,
            $_POST['youtube'] ?? ''
        ]);
        $_SESSION['success'] = 'Sermon added successfully!';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    // Edit sermon
    if (isset($_POST['edit_sermon'])) {
        // Get current thumbnail
        $stmt = $pdo->prepare("SELECT thumbnail FROM sermons WHERE id = ?");
        $stmt->execute([$_POST['sermon_id']]);
        $current = $stmt->fetch();
        $thumbnailPath = $current ? $current['thumbnail'] : '';
        // Handle file upload
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['thumbnail']['tmp_name'];
            $fileName = basename($_FILES['thumbnail']['name']);
            $fileSize = $_FILES['thumbnail']['size'];
            $fileType = mime_content_type($fileTmp);
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($fileType, $allowedTypes) && $fileSize <= 2*1024*1024) {
                $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                $newName = uniqid('sermon_', true) . '.' . $ext;
                $dest = __DIR__ . '/../uploads/' . $newName;
                if (move_uploaded_file($fileTmp, $dest)) {
                    $thumbnailPath = './uploads/' . $newName;
                }
            }
        }
        $stmt = $pdo->prepare("UPDATE sermons SET title = ?, date = ?, speaker = ?, content = ?, thumbnail = ?, youtube = ? WHERE id = ?");
        $stmt->execute([
            $_POST['title'],
            $_POST['date'],
            $_POST['speaker'],
            $_POST['content'],
            $thumbnailPath,
            $_POST['youtube'] ?? '',
            $_POST['sermon_id']
        ]);
        $_SESSION['success'] = 'Sermon updated successfully!';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Handle GET operations
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'delete' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("DELETE FROM sermons WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $_SESSION['success'] = 'Sermon deleted successfully!';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    if ($_GET['action'] === 'logout') {
        session_destroy();
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Get data
$sermons = $pdo->query("SELECT * FROM sermons ORDER BY date DESC")->fetchAll();
$sermonCount = count($sermons);
$thisMonthCount = $pdo->query("SELECT COUNT(*) FROM sermons WHERE MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE())")->fetchColumn();
$speakerCount = $pdo->query("SELECT COUNT(DISTINCT speaker) FROM sermons")->fetchColumn();

// Get sermon for editing
$editSermon = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM sermons WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editSermon = $stmt->fetch();
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church Admin Dashboard</title>
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
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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
        
        .sidebar.active {
            transform: translateX(0);
        }
        
        .sidebar-header {
            padding: 2rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header h3 {
            color: var(--white);
            font-size: 1.5rem;
            margin: 0.5rem 0;
        }
        
        .sidebar-header i {
            font-size: 2.5rem;
            color: var(--accent-gold);
            margin-bottom: 0.5rem;
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
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
        
        .sidebar-menu .nav-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 1.1rem;
        }
        
        .main-content {
            margin-left: 0;
            min-height: 100vh;
            transition: var(--transition);
        }
        
        .main-content.sidebar-open {
            margin-left: 280px;
        }
        
        .navbar {
            background: var(--white) !important;
            box-shadow: var(--box-shadow);
            border-bottom: 1px solid rgba(96, 55, 158, 0.1);
            padding: 1rem 2rem;
        }
        
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            font-size: 1.5rem;
            color: var(--primary-purple) !important;
        }
        
        .content-area {
            padding: 2rem;
            max-width: 100%;
            overflow-x: hidden;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--primary-purple), var(--accent-purple));
            color: var(--white);
            border-radius: var(--border-radius);
            padding: 2rem;
            text-align: center;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
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
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 20px 20px;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 40px rgba(76, 29, 149, 0.2);
        }
        
        .stat-card i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--accent-gold);
        }
        
        .stat-card h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0.5rem 0;
            color: var(--white);
        }
        
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            overflow: hidden;
        }
        
        .card:hover {
            box-shadow: 0 8px 35px rgba(76, 29, 149, 0.15);
            transform: translateY(-2px);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-purple), var(--accent-purple));
            color: var(--white);
            border: none;
            padding: 1.5rem;
        }
        
        .card-header h5 {
            margin: 0;
            color: var(--white);
            display: flex;
            align-items: center;
        }
        
        .card-header i {
            margin-right: 0.5rem;
            color: var(--accent-gold);
        }
        
        .form-control {
            border: 2px solid rgba(96, 55, 158, 0.1);
            border-radius: 12px;
            padding: 0.8rem 1rem;
            transition: var(--transition);
        }
        
        .form-control:focus {
            border-color: var(--accent-purple);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-purple), var(--accent-purple));
            border: none;
            border-radius: 12px;
            padding: 0.8rem 1.5rem;
            font-weight: 600;
            transition: var(--transition);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--accent-purple), var(--primary-purple));
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(96, 55, 158, 0.3);
        }
        
        .table {
            border-radius: 12px;
            overflow: hidden;
        }
        
        .table th {
            background: var(--light-purple);
            border: none;
            font-weight: 600;
            color: var(--text-dark);
            padding: 1rem;
        }
        
        .table td {
            border-color: rgba(96, 55, 158, 0.1);
            padding: 1rem;
            vertical-align: middle;
        }
        
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 500;
        }
        
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: var(--white);
        }
        
        .btn-outline-primary {
            color: var(--primary-purple);
            border: 2px solid var(--primary-purple);
            border-radius: 8px;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-purple);
            border-color: var(--primary-purple);
        }
        
        .btn-outline-danger {
            color: #ef4444;
            border: 2px solid #ef4444;
            border-radius: 8px;
        }
        
        .btn-outline-danger:hover {
            background: #ef4444;
            border-color: #ef4444;
        }
        
        .modal-content {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: 0 20px 60px rgba(76, 29, 149, 0.2);
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--primary-purple), var(--accent-purple));
            color: var(--white);
            border: none;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-light);
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                transform: translateX(-100%);
            }
            
            .main-content.sidebar-open {
                margin-left: 0;
            }
            
            .content-area {
                padding: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .stat-card {
                padding: 1.5rem;
            }
            
            .table-responsive {
                border-radius: 12px;
            }
        }
        
        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 280px;
            }
            
            .navbar .btn[data-bs-toggle="offcanvas"] {
                display: none;
            }
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
            <a href="#" class="nav-link active">
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
            <a href="volunteer_forms.php" class="nav-link">
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
        <!-- Top Navigation - Match main site branding -->
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
                        <li class="nav-item"><a class="nav-link active" href="/admin/index.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="volunteer_forms.php">Volunteer Forms</a></li>
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
        <div class="content-area">
            <!-- Success Message -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= htmlspecialchars($_SESSION['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-microphone"></i>
                    <h3><?= $sermonCount ?></h3>
                    <p>Total Sermons</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-calendar-check"></i>
                    <h3><?= $thisMonthCount ?></h3>
                    <p>This Month</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-user-tie"></i>
                    <h3><?= $speakerCount ?></h3>
                    <p>Speakers</p>
                </div>
            </div>

            <!-- Add/Edit Sermon Form -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white border-bottom-0">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-<?= $editSermon ? 'edit' : 'plus' ?> me-2 text-primary"></i>
                        <span class="fw-bold" style="font-family: 'Playfair Display', serif; letter-spacing: 0.5px;">
                            <?= $editSermon ? 'Edit Sermon' : 'Add New Sermon' ?>
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data" action="save_sermon.php" class="needs-validation" novalidate>
                        <?php if ($editSermon): ?>
                            <input type="hidden" name="sermon_id" value="<?= $editSermon['id'] ?>">
                        <?php endif; ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Sermon Title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="e.g. The Power of Faith" value="<?= $editSermon ? htmlspecialchars($editSermon['title']) : '' ?>" required>
                                <div class="invalid-feedback">Please provide a sermon title.</div>
                            </div>
                            <div class="col-md-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date" value="<?= $editSermon ? $editSermon['date'] : date('Y-m-d') ?>" required>
                                <div class="invalid-feedback">Please select a date.</div>
                            </div>
                            <div class="col-md-3">
                                <label for="speaker" class="form-label">Speaker</label>
                                <input type="text" class="form-control" id="speaker" name="speaker" placeholder="e.g. Pastor John" value="<?= $editSermon ? htmlspecialchars($editSermon['speaker']) : '' ?>" required>
                                <div class="invalid-feedback">Please provide the speaker's name.</div>
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="content" class="form-label">Sermon Content</label>
                            <textarea class="form-control" id="content" name="content" rows="6" placeholder="Enter the full sermon text or summary here..." required><?= $editSermon ? htmlspecialchars($editSermon['content']) : '' ?></textarea>
                            <div class="invalid-feedback">Please enter the sermon content.</div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
    <label for="thumbnail" class="form-label">Thumbnail Image <span class="text-muted small">(optional, JPG/PNG, max 2MB)</span></label>
    <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
    <?php if ($editSermon && !empty($editSermon['thumbnail'])): ?>
        <div class="mt-2">
            <img src="<?= htmlspecialchars($editSermon['thumbnail']) ?>" alt="Current Thumbnail" style="max-width: 120px; max-height: 120px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.07);">
            <div class="form-text">Current thumbnail. Upload a new image to replace.</div>
        </div>
    <?php endif; ?>
    <div class="form-text">Upload a JPG or PNG image. Max size: 2MB.</div>
</div>
                            <div class="col-md-6">
                                <label for="youtube" class="form-label">YouTube Link <span class="text-muted small">(optional)</span></label>
                                <input type="url" class="form-control" id="youtube" name="youtube" placeholder="https://youtube.com/watch?v=..." value="<?= $editSermon && !empty($editSermon['youtube']) ? htmlspecialchars($editSermon['youtube']) : '' ?>">
                                <div class="form-text">Paste the YouTube link for this sermon if available.</div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" name="<?= $editSermon ? 'edit_sermon' : 'add_sermon' ?>" class="btn btn-primary px-4">
                                <i class="fas fa-<?= $editSermon ? 'save' : 'plus' ?> me-2"></i>
                                <?= $editSermon ? 'Update Sermon' : 'Add Sermon' ?>
                            </button>
                            <?php if ($editSermon): ?>
                                <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
            <script>
                // Bootstrap form validation
                (() => {
                  'use strict';
                  const forms = document.querySelectorAll('.needs-validation');
                  Array.from(forms).forEach(form => {
                    form.addEventListener('submit', event => {
                      if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                      }
                      form.classList.add('was-validated');
                    }, false);
                  });
                })();
            </script>

            <!-- Sermon Cards Preview (matches public site style) -->
            <?php if (!empty($sermons)): ?>
            <div class="row g-4 mb-4">
                <?php foreach ($sermons as $sermon): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="sermon-card">
                            <div class="sermon-thumbnail">
                                <?php
                                    // Use thumbnail if present, else default
                                    $thumb = !empty($sermon['thumbnail']) ? htmlspecialchars('../uploads/'.$sermon['thumbnail']) : '../uploads/default_sermon.jpeg';
                                ?>
                                <img src="<?= $thumb ?>" alt="Sermon Thumbnail" class="sermon-thumb">
                                <?php if (!empty($sermon['youtube'])): ?>
                                    <a href="<?= htmlspecialchars($sermon['youtube']) ?>" class="youtube-link" target="_blank">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="sermon-header">
                                <i class="fas fa-microphone-alt sermon-icon"></i>
                            </div>
                            <div class="sermon-content">
                                <h4><?= htmlspecialchars($sermon['title']) ?></h4>
                                <p class="sermon-meta">Preached by <?= htmlspecialchars($sermon['speaker']) ?> &bull; <?= date('F j, Y', strtotime($sermon['date'])) ?></p>
                                <p class="sermon-description">
                                    <?= nl2br(htmlspecialchars(mb_strimwidth($sermon['content'], 0, 120, '...'))) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Sermons List -->
            <div class="card">
                <div class="card-header">
                    <h5>
                        <i class="fas fa-list"></i>
                        All Sermons (<?= $sermonCount ?>)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($sermons)): ?>
                        <div class="empty-state">
                            <i class="fas fa-microphone"></i>
                            <h5>No sermons yet</h5>
                            <p>Add your first sermon using the form above.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Title</th>
                                        <th>Speaker</th>
                                        <th class="d-none d-md-table-cell">Content Preview</th>
                                        <th width="120">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sermons as $sermon): ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">
                                                <?= date('M j, Y', strtotime($sermon['date'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($sermon['title']) ?></strong>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-tie me-2 text-muted"></i>
                                                <?= htmlspecialchars($sermon['speaker']) ?>
                                            </div>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <small class="text-muted">
                                                <?= substr(htmlspecialchars($sermon['content']), 0, 80) ?>...
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        onclick="viewSermon(<?= htmlspecialchars(json_encode($sermon)) ?>)" 
                                                        title="View">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="?edit=<?= $sermon['id'] ?>" 
                                                   class="btn btn-sm btn-outline-secondary" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteSermon(<?= $sermon['id'] ?>)" 
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- View Sermon Modal -->
    <div class="modal fade" id="sermonModal" tabindex="-1" aria-labelledby="sermonModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sermonModalLabel"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="sermonModalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Database Setup Instructions -->
    <?php if ($pdo->query("SELECT COUNT(*) FROM sermons")->fetchColumn() == 0): ?>
    <div class="modal fade" id="setupModal" tabindex="-1" aria-labelledby="setupModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="setupModalLabel">
                        <i class="fas fa-info-circle me-2"></i>
                        Database Setup Complete
                    </h5>
                </div>
                <div class="modal-body">
                    <p>Your Church Admin Dashboard is ready! The database tables have been created automatically.</p>
                    <div class="alert alert-info">
                        <strong>Database Configuration:</strong><br>
                        - Host: <?= htmlspecialchars($host) ?><br>
                        - Database: <?= htmlspecialchars($dbname) ?><br>
                        - Tables: admins, sermons, members, events
                    </div>
                    <p>You can now start adding sermons and managing your church data.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Get Started</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('sidebar-open');
        }

        // View sermon modal
        function viewSermon(sermon) {
            const modal = new bootstrap.Modal(document.getElementById('sermonModal'));
            document.getElementById('sermonModalLabel').textContent = sermon.title;
            
            const formattedDate = new Date(sermon.date).toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            document.getElementById('sermonModalBody').innerHTML = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Date</h6>
                        <p class="mb-0">${formattedDate}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Speaker</h6>
                        <p class="mb-0">
                            <i class="fas fa-user-tie me-2 text-muted"></i>
                            ${sermon.speaker}
                        </p>
                    </div>
                </div>
                <div class="mb-3">
                    <h6 class="text-muted mb-2">Content</h6>
                    <div class="bg-light p-3 rounded" style="max-height: 400px; overflow-y: auto;">
                        ${sermon.content.replace(/\n/g, '<br>')}
                    </div>
                </div>
                <div class="text-muted small">
                    <i class="fas fa-clock me-1"></i>
                    Created: ${new Date(sermon.created_at).toLocaleString()}
                    ${sermon.updated_at !== sermon.created_at ? 
                        `<br><i class="fas fa-edit me-1"></i>Updated: ${new Date(sermon.updated_at).toLocaleString()}` 
                        : ''
                    }
                </div>
            `;
            
            modal.show();
        }

        // Delete sermon
        function deleteSermon(id) {
            if (confirm('Are you sure you want to delete this sermon? This action cannot be undone.')) {
                window.location.href = `?action=delete&id=${id}`;
            }
        }

        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.querySelector('.btn-close')) {
                        alert.querySelector('.btn-close').click();
                    }
                }, 5000);
            });

            // Show setup modal if no sermons exist
            <?php if ($pdo->query("SELECT COUNT(*) FROM sermons")->fetchColumn() == 0): ?>
            const setupModal = new bootstrap.Modal(document.getElementById('setupModal'));
            setupModal.show();
            <?php endif; ?>
        });

        // Responsive sidebar behavior
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            if (window.innerWidth >= 992) {
                sidebar.classList.remove('active');
                mainContent.classList.remove('sidebar-open');
            }
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('[onclick="toggleSidebar()"]');
            
            if (window.innerWidth < 992 && 
                sidebar.classList.contains('active') && 
                !sidebar.contains(e.target) && 
                !toggleBtn.contains(e.target)) {
                toggleSidebar();
            }
        });

        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;
                    
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            field.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            field.classList.remove('is-invalid');
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        alert('Please fill in all required fields.');
                    }
                });
            });
        });
    </script>
</body>
</html>