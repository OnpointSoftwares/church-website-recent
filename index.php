<?php
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request = trim($request, '/');

if ($request === '' || $request === 'index') {
    $request = 'index';
}
$templatePath = __DIR__ . '/templates/' . $request . '.php';
if (!file_exists($templatePath)) {
    $templatePath = __DIR__ . '/templates/' . $request . '/index.php';
}
if (!file_exists($templatePath) && ($request === '' || $request === 'index')) {
    $templatePath = __DIR__ . '/templates/index.php';
}
if (file_exists($templatePath)) {
    include $templatePath;
    exit;
}
http_response_code(404);
echo "<h1>404 Not Found</h1>";
echo "<p>The page you requested could not be found.</p>";
