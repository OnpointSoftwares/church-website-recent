<?php
// index.php - Main entry point for the church website

// Get the requested path, strip query string
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request = trim($request, '/');

// Default to 'about' if root is accessed
if ($request === '' || $request === 'index') {
    $request = 'index'; // Change to 'home' if you have a home.html
}

// Try templates/{request}.php first
$templatePath = __DIR__ . '/templates/' . $request . '.php';

// If not found, try templates/{request}/index.php (for directory-style pages)
if (!file_exists($templatePath)) {
    $templatePath = __DIR__ . '/templates/' . $request . '/index.php';
}

// If still not found, try root index.php (for homepage)
if (!file_exists($templatePath) && ($request === '' || $request === 'index')) {
    $templatePath = __DIR__ . '/templates/index.php';
}

// Show the template if it exists
if (file_exists($templatePath)) {
    include $templatePath;
    exit;
}

// 404 Not Found
http_response_code(404);
echo "<h1>404 Not Found</h1>";
echo "<p>The page you requested could not be found.</p>";
