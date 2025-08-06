<?php
// volunteers_api.php: Handles AJAX for volunteer portal
session_start();

$response = ['success' => false, 'message' => 'Unknown error'];

// Commitment form upload
if (isset($_POST['action']) && $_POST['action'] === 'upload_commitment') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['file']['tmp_name'];
        $fileName = basename($_FILES['file']['name']);
        $fileSize = $_FILES['file']['size'];
        $allowedTypes = ['application/pdf'];
        $fileType = mime_content_type($fileTmp);
        if (in_array($fileType, $allowedTypes) && $fileSize <= 5*1024*1024) {
            $destDir = __DIR__ . '/uploads/volunteers/';
            if (!is_dir($destDir)) mkdir($destDir, 0775, true);
            $newName = uniqid('commit_', true) . '.pdf';
            $dest = $destDir . $newName;
            if (move_uploaded_file($fileTmp, $dest)) {
                $response = ['success' => true, 'message' => 'Commitment form uploaded successfully!'];
            } else {
                $response['message'] = 'Failed to upload file.';
            }
        } else {
            $response['message'] = 'Only PDF files up to 5MB are allowed.';
        }
    } else {
        $response['message'] = 'No file selected or upload error.';
    }
    echo json_encode($response); exit;
}

// Suggestions/prayer requests
if (isset($_POST['action']) && $_POST['action'] === 'submit_message') {
    $msgType = $_POST['msg_type'] ?? '';
    $msgContent = trim($_POST['message'] ?? '');
    if ($msgType && $msgContent) {
        $destDir = __DIR__ . '/uploads/volunteers/';
        if (!is_dir($destDir)) mkdir($destDir, 0775, true);
        $filename = $destDir . $_SESSION['volunteer']['name'] . '_' . $msgType . '_' . date('Ymd_His') . '_' . uniqid() . '.txt';
        file_put_contents($filename, $msgContent);
        $response = ['success' => true, 'message' => ucfirst($msgType) . ' submitted successfully!'];
    } else {
        $response['message'] = 'Please enter your message.';
    }
    echo json_encode($response); exit;
}

// Volunteer signup: save to DB and send OTP to admin
if (isset($_POST['action']) && $_POST['action'] === 'signup') {
    require_once __DIR__.'/config.php';
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $ministry = trim($_POST['ministry'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$name || !$email || !$password || !$ministry) {
        echo json_encode(['success'=>false,'message'=>'All fields are required.']); exit;
    }
    $otp = random_int(100000,999999);
    try {
        // DB connection
        if (!isset($pdo)) {
            $host = defined('DB_HOST') ? DB_HOST : 'localhost';
            $db = defined('DB_NAME') ? DB_NAME : 'kazrxdvk_church_management';
            $user = defined('DB_USER') ? DB_USER : 'kazrxdvk_vincent';
            $pass = defined('DB_PASS') ? DB_PASS : '@Admin@2025';
            $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        // Check for duplicate
        $stmt = $pdo->prepare("SELECT id FROM volunteers WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            echo json_encode(['success'=>false,'message'=>'Email already registered.']); exit;
        }
        // Insert
        $stmt = $pdo->prepare("INSERT INTO volunteers (name, email, phone, ministry, password, otp, verified) VALUES (?, ?, ?, ?, ?, ?, 0)");
        $stmt->execute([$name, $email, $phone, $ministry, password_hash($password, PASSWORD_DEFAULT), $otp]);
    } catch (Exception $e) {
        echo json_encode(['success'=>false,'message'=>'Error: ' . $e->getMessage()]); exit;
    }
    // Send OTP email using PHPMailer
    require_once __DIR__.'/vendor/autoload.php';
    $mailcfg = require __DIR__.'/phpmailer_config.php';
    $subject = "New Volunteer Signup - OTP Verification";
    $msg = "A new volunteer has registered.\n\nName: $name\nEmail: $email\nPhone: $phone\nOTP: $otp\n\nPlease verify this user in the admin portal.";
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $mailcfg['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $mailcfg['username'];
        $mail->Password = $mailcfg['password'];
        $mail->SMTPSecure = $mailcfg['secure'];
        $mail->Port = $mailcfg['port'];
        $mail->setFrom($mailcfg['from'], $mailcfg['from_name']);
        $mail->addAddress($mailcfg['admin_email']);
        $mail->Subject = $subject;
        $mail->Body = $msg;
        $mail->send();
        echo json_encode(['success'=>true,'message'=>'Registration received. Awaiting admin verification.']); exit;
    } catch (Exception $e) {
        echo json_encode(['success'=>false,'message'=>'Email error: ' . $mail->ErrorInfo]); exit;
    }
}

// Volunteer OTP verification by admin (DB)
if (isset($_POST['action']) && $_POST['action'] === 'verify_otp') {
    require_once __DIR__.'/config.php';
    $email = trim($_POST['email'] ?? '');
    $otp = trim($_POST['otp'] ?? '');
    if (!$email || !$otp) {
        echo json_encode(['success'=>false,'message'=>'Email and OTP required.']); exit;
    }
    try {
        if (!isset($pdo)) {
            $host = defined('DB_HOST') ? DB_HOST : 'localhost';
            $db = defined('DB_NAME') ? DB_NAME : 'kazrxdvk_church_management';
            $user = defined('DB_USER') ? DB_USER : 'kazrxdvk_vincent';
            $pass = defined('DB_PASS') ? DB_PASS : '@Admin@2025';
            $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        $stmt = $pdo->prepare("SELECT id, verified FROM volunteers WHERE email = ? AND otp = ?");
        $stmt->execute([$email, $otp]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            if ($row['verified']) {
                echo json_encode(['success'=>false,'message'=>'Volunteer already verified.']); exit;
            }
            $update = $pdo->prepare("UPDATE volunteers SET verified = 1 WHERE id = ?");
            $update->execute([$row['id']]);
            echo json_encode(['success'=>true,'message'=>'Volunteer verified.']); exit;
        } else {
            echo json_encode(['success'=>false,'message'=>'Invalid OTP or email.']); exit;
        }
    } catch (Exception $e) {
        echo json_encode(['success'=>false,'message'=>'Error: ' . $e->getMessage()]); exit;
    }
}

// Login from volunteers table (DB)
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    require_once __DIR__.'/config.php';
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$email || !$password) {
        echo json_encode(['success'=>false,'message'=>'Email and password required.']); exit;
    }
    try {
        if (!isset($pdo)) {
            $host = defined('DB_HOST') ? DB_HOST : 'localhost';
            $db = defined('DB_NAME') ? DB_NAME : 'kazrxdvk_church_management';
            $user = defined('DB_USER') ? DB_USER : 'kazrxdvk_vincent';
            $pass = defined('DB_PASS') ? DB_PASS : '@Admin@2025';
            $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        $stmt = $pdo->prepare("SELECT id, name, email, phone, ministry, password, verified, created_at FROM volunteers WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            if (!$user['verified']) {
                echo json_encode(['success'=>false,'message'=>'Account not yet verified by admin.']); exit;
            }
            $_SESSION['volunteer'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'ministry' => $user['ministry'],
                'role' => 'Active Volunteer',
                'join_date' => date('F Y', strtotime($user['created_at'])),
                'avatar' => strtoupper(substr($user['name'],0,1)) . strtoupper(substr($user['email'],0,1))
            ];
            echo json_encode(['success'=>true,'user'=>$_SESSION['volunteer']]); exit;
        } else {
            echo json_encode(['success'=>false,'message'=>'Invalid credentials.']); exit;
        }
    } catch (Exception $e) {
        echo json_encode(['success'=>false,'message'=>'Error: ' . $e->getMessage()]); exit;
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'logout') {
    unset($_SESSION['volunteer']);
    $response = ['success' => true];
    echo json_encode($response); exit;
}

// Registration endpoint can be added similarly.
