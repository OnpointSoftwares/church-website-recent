<?php
header('Content-Type: application/json');

// Database config
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
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed.']);
    exit;
}

// Fetch latest 10 sermons (can adjust as needed)
$stmt = $pdo->query("SELECT id, title, date, speaker, content, thumbnail, youtube FROM sermons ORDER BY date DESC LIMIT 10");
$sermons = $stmt->fetchAll();

echo json_encode(['sermons' => $sermons]);
