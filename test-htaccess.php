<?php
/**
 * Test if .htaccess is being processed
 */

// Set a custom header to test if .htaccess is working
header('X-Test-Header: PHP-Generated');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test .htaccess Processing</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test { background: #f0f0f0; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <h1>Test .htaccess Processing</h1>
    
    <div class="test">
        <h3>Server Information:</h3>
        <p><strong>Server:</strong> <?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?></p>
        <p><strong>Document Root:</strong> <?= $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown' ?></p>
        <p><strong>Current Directory:</strong> <?= __DIR__ ?></p>
        <p><strong>Request URI:</strong> <?= $_SERVER['REQUEST_URI'] ?? 'Unknown' ?></p>
    </div>
    
    <div class="test">
        <h3>.htaccess File Check:</h3>
        <?php
        $htaccessPath = __DIR__ . '/.htaccess';
        if (file_exists($htaccessPath)):
            $size = filesize($htaccessPath);
            $modified = date('Y-m-d H:i:s', filemtime($htaccessPath));
        ?>
            <p class="success">✅ .htaccess file exists</p>
            <p><strong>Path:</strong> <?= $htaccessPath ?></p>
            <p><strong>Size:</strong> <?= $size ?> bytes</p>
            <p><strong>Last Modified:</strong> <?= $modified ?></p>
        <?php else: ?>
            <p class="error">❌ .htaccess file not found</p>
        <?php endif; ?>
    </div>
    
    <div class="test">
        <h3>Headers Test:</h3>
        <p>Check browser developer tools Network tab to see if custom headers from .htaccess are present:</p>
        <ul>
            <li><strong>X-Test-Header:</strong> Should be "PHP-Generated" (from this PHP file)</li>
            <li><strong>Content-Security-Policy:</strong> Should be the updated policy from .htaccess</li>
            <li><strong>Referrer-Policy:</strong> Should be "strict-origin-when-cross-origin" from .htaccess</li>
        </ul>
    </div>
    
    <div class="test">
        <h3>CSP Test:</h3>
        <p>Try loading Bootstrap CSS:</p>
        <button onclick="testCSP()">Test Bootstrap Loading</button>
        <div id="csp-result"></div>
    </div>
    
    <script>
        function testCSP() {
            const resultDiv = document.getElementById('csp-result');
            resultDiv.innerHTML = '<p>Testing...</p>';
            
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css';
            
            link.onload = function() {
                resultDiv.innerHTML = '<p style="color: green;">✅ Bootstrap CSS loaded successfully - CSP is working!</p>';
            };
            
            link.onerror = function() {
                resultDiv.innerHTML = '<p style="color: red;">❌ Bootstrap CSS failed to load - Check console for CSP error</p>';
            };
            
            document.head.appendChild(link);
            
            // Also test with fetch to see headers
            fetch(window.location.href, { method: 'HEAD' })
                .then(response => {
                    let headerInfo = '<h4>Response Headers:</h4><ul>';
                    for (let [key, value] of response.headers.entries()) {
                        headerInfo += `<li><strong>${key}:</strong> ${value}</li>`;
                    }
                    headerInfo += '</ul>';
                    resultDiv.innerHTML += headerInfo;
                })
                .catch(err => {
                    resultDiv.innerHTML += '<p style="color: orange;">Could not fetch headers: ' + err.message + '</p>';
                });
        }
        
        // Auto-run the test
        setTimeout(testCSP, 1000);
    </script>
</body>
</html>
