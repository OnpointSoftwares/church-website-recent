<?php
/**
 * Debug Headers - Check what CSP headers are being sent
 */

// Get all response headers that will be sent
$headers = headers_list();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Headers - CSP Check</title>
    <style>
        body { font-family: monospace; margin: 20px; }
        .header { background: #f0f0f0; padding: 10px; margin: 5px 0; }
        .csp { background: #ffe6e6; padding: 10px; margin: 10px 0; }
        .info { background: #e6f3ff; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Debug Headers - CSP Configuration Check</h1>
    
    <div class="info">
        <h3>Current Time:</h3>
        <p><?= date('Y-m-d H:i:s') ?></p>
    </div>
    
    <div class="info">
        <h3>Server Information:</h3>
        <p><strong>Server Software:</strong> <?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?></p>
        <p><strong>Document Root:</strong> <?= $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown' ?></p>
        <p><strong>Script Filename:</strong> <?= $_SERVER['SCRIPT_FILENAME'] ?? 'Unknown' ?></p>
        <p><strong>Request URI:</strong> <?= $_SERVER['REQUEST_URI'] ?? 'Unknown' ?></p>
    </div>
    
    <div class="info">
        <h3>Response Headers Being Sent:</h3>
        <?php if (empty($headers)): ?>
            <p>No headers captured (headers may have already been sent)</p>
        <?php else: ?>
            <?php foreach ($headers as $header): ?>
                <div class="header <?= strpos($header, 'Content-Security-Policy') !== false ? 'csp' : '' ?>">
                    <?= htmlspecialchars($header) ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="info">
        <h3>Check .htaccess File:</h3>
        <?php
        $htaccessPath = __DIR__ . '/.htaccess';
        if (file_exists($htaccessPath)):
            $htaccessContent = file_get_contents($htaccessPath);
            $cspLines = [];
            foreach (explode("\n", $htaccessContent) as $line) {
                if (stripos($line, 'Content-Security-Policy') !== false) {
                    $cspLines[] = $line;
                }
            }
        ?>
            <p><strong>.htaccess file exists:</strong> <?= $htaccessPath ?></p>
            <p><strong>CSP lines in .htaccess:</strong></p>
            <?php if (empty($cspLines)): ?>
                <p>No CSP configuration found in .htaccess</p>
            <?php else: ?>
                <?php foreach ($cspLines as $line): ?>
                    <div class="csp"><?= htmlspecialchars(trim($line)) ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php else: ?>
            <p><strong>.htaccess file not found at:</strong> <?= $htaccessPath ?></p>
        <?php endif; ?>
    </div>
    
    <div class="info">
        <h3>Admin Config CSP:</h3>
        <?php
        $adminConfigPath = __DIR__ . '/admin/config.php';
        if (file_exists($adminConfigPath)):
        ?>
            <p><strong>Admin config exists:</strong> <?= $adminConfigPath ?></p>
            <p>Check if admin config is being included...</p>
        <?php else: ?>
            <p><strong>Admin config not found at:</strong> <?= $adminConfigPath ?></p>
        <?php endif; ?>
    </div>
    
    <div class="info">
        <h3>Test External Resources:</h3>
        <p>Try loading external resources to see CSP violations:</p>
        <button onclick="testBootstrap()">Test Bootstrap CSS</button>
        <button onclick="testJQuery()">Test jQuery</button>
        <div id="test-results"></div>
    </div>
    
    <script>
        function testBootstrap() {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css';
            link.onload = () => {
                document.getElementById('test-results').innerHTML += '<p style="color: green;">✅ Bootstrap CSS loaded successfully</p>';
            };
            link.onerror = () => {
                document.getElementById('test-results').innerHTML += '<p style="color: red;">❌ Bootstrap CSS failed to load (check console for CSP error)</p>';
            };
            document.head.appendChild(link);
        }
        
        function testJQuery() {
            const script = document.createElement('script');
            script.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
            script.onload = () => {
                document.getElementById('test-results').innerHTML += '<p style="color: green;">✅ jQuery loaded successfully</p>';
            };
            script.onerror = () => {
                document.getElementById('test-results').innerHTML += '<p style="color: red;">❌ jQuery failed to load (check console for CSP error)</p>';
            };
            document.head.appendChild(script);
        }
        
        // Show current headers from browser perspective
        console.log('=== DEBUG: Current page headers ===');
        fetch(window.location.href, { method: 'HEAD' })
            .then(response => {
                console.log('Response headers:');
                for (let [key, value] of response.headers.entries()) {
                    console.log(`${key}: ${value}`);
                    if (key.toLowerCase().includes('content-security-policy')) {
                        document.getElementById('test-results').innerHTML += `<div class="csp"><strong>Actual CSP Header:</strong><br>${value}</div>`;
                    }
                }
            })
            .catch(err => console.error('Failed to fetch headers:', err));
    </script>
</body>
</html>
