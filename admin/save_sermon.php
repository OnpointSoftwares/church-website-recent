<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();

// Database config (reuse from index.php)
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

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php'); exit;
}

// Handle add or edit sermon
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $date = $_POST['date'] ?? '';
    $speaker = $_POST['speaker'] ?? '';
    $content = $_POST['content'] ?? '';
    $youtube = $_POST['youtube'] ?? '';
    $thumbnailPath = '';

    // Handle thumbnail upload if present
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE) {
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
                $thumbnailPath = 'uploads/' . $newName;
            }
        }
    }

    // Add sermon
    if (isset($_POST['add_sermon'])) {
        $stmt = $pdo->prepare("INSERT INTO sermons (title, date, speaker, content, thumbnail, youtube) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $title,
            $date,
            $speaker,
            $content,
            $thumbnailPath,
            $youtube
        ]);
        $_SESSION['success'] = 'Sermon added successfully!';
        header('Location: index.php');
        exit;
    }

    // Edit sermon
    if (isset($_POST['edit_sermon']) && isset($_POST['sermon_id'])) {
        $sermonId = $_POST['sermon_id'];
        // If no new thumbnail uploaded, keep the old one
        if (!$thumbnailPath) {
            $stmt = $pdo->prepare("SELECT thumbnail FROM sermons WHERE id = ?");
            $stmt->execute([$sermonId]);
            $row = $stmt->fetch();
            $thumbnailPath = $row ? $row['thumbnail'] : '';
        }
        $stmt = $pdo->prepare("UPDATE sermons SET title = ?, date = ?, speaker = ?, content = ?, thumbnail = ?, youtube = ? WHERE id = ?");
        $stmt->execute([
            $title,
            $date,
            $speaker,
            $content,
            $thumbnailPath,
            $youtube,
            $sermonId
        ]);
        $_SESSION['success'] = 'Sermon updated successfully!';
        header('Location: index.php');
        exit;
    }
}
// Fallback
header('Location: index.php');
exit;

?>