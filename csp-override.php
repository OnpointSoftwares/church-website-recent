<?php
// Aggressive CSP override - removes all CSP policies
header_remove('Content-Security-Policy');
header_remove('Content-Security-Policy-Report-Only');

// Prevent any CSP from being set
if (function_exists('apache_response_headers')) {
    $headers = apache_response_headers();
    if (isset($headers['Content-Security-Policy'])) {
        header_remove('Content-Security-Policy');
    }
}

// Set explicit no-CSP header
header('X-CSP-Disabled: true');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSP Override Test</title>
    
    <!-- Test external resources -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Roboto', Arial, sans-serif; margin: 20px; }
        .status { padding: 15px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .info { background: #d1ecf1; color: #0c5460; }
    </style>
</head>
<body>
    <div class="container">
        <h1>CSP Override Test</h1>
        
        <div class="status info">
            <h4>Server Information</h4>
            <p><strong>Server:</strong> <?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?></p>
            <p><strong>PHP Version:</strong> <?= phpversion() ?></p>
            <p><strong>Time:</strong> <?= date('Y-m-d H:i:s') ?></p>
        </div>
        
        <div class="status" id="test-status">
            <h4>Resource Loading Test</h4>
            <div id="bootstrap-test">🔄 Testing Bootstrap...</div>
            <div id="jquery-test">🔄 Testing jQuery...</div>
            <div id="fonts-test">🔄 Testing Google Fonts...</div>
        </div>
        
        <div class="status info">
            <h4>Instructions</h4>
            <p>1. Check browser console for CSP violations</p>
            <p>2. If violations still appear, contact hosting provider</p>
            <p>3. Server-level CSP cannot be overridden by application code</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        console.log('=== CSP Override Test ===');
        
        // Test Bootstrap
        setTimeout(() => {
            const testEl = document.createElement('div');
            testEl.className = 'btn btn-primary d-none';
            document.body.appendChild(testEl);
            
            const styles = window.getComputedStyle(testEl);
            const success = styles.display === 'none';
            
            document.getElementById('bootstrap-test').innerHTML = success ? 
                '✅ Bootstrap CSS loaded' : '❌ Bootstrap CSS blocked';
            
            document.body.removeChild(testEl);
        }, 500);
        
        // Test jQuery
        setTimeout(() => {
            const success = typeof jQuery !== 'undefined';
            document.getElementById('jquery-test').innerHTML = success ? 
                '✅ jQuery loaded' : '❌ jQuery blocked';
        }, 600);
        
        // Test Google Fonts
        setTimeout(() => {
            const testEl = document.createElement('div');
            testEl.style.fontFamily = 'Roboto';
            testEl.style.position = 'absolute';
            testEl.style.visibility = 'hidden';
            testEl.textContent = 'Test';
            document.body.appendChild(testEl);
            
            const font = window.getComputedStyle(testEl).fontFamily;
            const success = font.includes('Roboto');
            
            document.getElementById('fonts-test').innerHTML = success ? 
                '✅ Google Fonts loaded' : '❌ Google Fonts blocked';
            
            document.body.removeChild(testEl);
        }, 700);
        
        // Update status
        setTimeout(() => {
            const status = document.getElementById('test-status');
            const tests = status.querySelectorAll('div[id$="-test"]');
            const allPassed = Array.from(tests).every(test => test.innerHTML.includes('✅'));
            
            status.className = allPassed ? 'status success' : 'status error';
            
            if (!allPassed) {
                console.log('❌ CSP violations still present - likely server-level policy');
            }
        }, 1000);
        
        // Monitor CSP violations
        const originalError = console.error;
        console.error = function(...args) {
            if (args.some(arg => typeof arg === 'string' && arg.includes('Content Security Policy'))) {
                console.log('🚨 SERVER-LEVEL CSP DETECTED:', ...args);
                console.log('This CSP policy is set by the hosting provider/server and cannot be overridden by application code.');
                console.log('Contact your hosting provider to disable or modify the server-level CSP policy.');
            }
            originalError.apply(console, args);
        };
    </script>
</body>
</html>
