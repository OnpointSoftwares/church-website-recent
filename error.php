<?php
/**
 * Production Error Page
 * 
 * Handles all HTTP errors with proper logging and user-friendly messages
 */

// Get error code from URL parameter
$errorCode = $_GET['code'] ?? '500';
$errorCode = filter_var($errorCode, FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 400, 'max_range' => 599, 'default' => 500]
]);

// Define error messages
$errors = [
    403 => [
        'title' => 'Access Forbidden',
        'message' => 'You don\'t have permission to access this resource.',
        'icon' => 'fas fa-ban'
    ],
    404 => [
        'title' => 'Page Not Found',
        'message' => 'The page you are looking for could not be found.',
        'icon' => 'fas fa-search'
    ],
    500 => [
        'title' => 'Internal Server Error',
        'message' => 'Something went wrong on our end. Please try again later.',
        'icon' => 'fas fa-exclamation-triangle'
    ],
    503 => [
        'title' => 'Service Unavailable',
        'message' => 'The service is temporarily unavailable. Please try again later.',
        'icon' => 'fas fa-tools'
    ]
];

$error = $errors[$errorCode] ?? $errors[500];

// Log the error (in production, you might want to log to a file)
$logMessage = "HTTP Error $errorCode - " . $_SERVER['REQUEST_URI'] . " - IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
error_log($logMessage);

// Set appropriate HTTP status code
http_response_code($errorCode);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $error['title'] ?> - Church Admin System</title>
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
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 50%, var(--dark-purple) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-dark);
        }
        
        .error-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            padding: 3rem;
            text-align: center;
            max-width: 500px;
            margin: 2rem;
        }
        
        .error-icon {
            font-size: 4rem;
            color: var(--accent-purple);
            margin-bottom: 1.5rem;
        }
        
        .error-code {
            font-size: 6rem;
            font-weight: 700;
            color: var(--primary-purple);
            line-height: 1;
            margin-bottom: 1rem;
            font-family: 'Playfair Display', serif;
        }
        
        .error-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-dark);
            font-family: 'Playfair Display', serif;
        }
        
        .error-message {
            color: var(--text-light);
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .btn-home {
            background: linear-gradient(135deg, var(--primary-purple), var(--accent-purple));
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-home:hover {
            background: linear-gradient(135deg, var(--accent-purple), var(--primary-purple));
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(96, 55, 158, 0.3);
            color: white;
        }
        
        .support-info {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(96, 55, 158, 0.1);
            font-size: 0.875rem;
            color: var(--text-light);
        }
        
        @media (max-width: 576px) {
            .error-container {
                margin: 1rem;
                padding: 2rem;
            }
            
            .error-code {
                font-size: 4rem;
            }
            
            .error-title {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="<?= $error['icon'] ?>"></i>
        </div>
        
        <div class="error-code"><?= $errorCode ?></div>
        
        <h1 class="error-title"><?= htmlspecialchars($error['title']) ?></h1>
        
        <p class="error-message">
            <?= htmlspecialchars($error['message']) ?>
        </p>
        
        <a href="/" class="btn-home">
            <i class="fas fa-home"></i>
            Return to Homepage
        </a>
        
        <?php if ($errorCode >= 500): ?>
        <div class="support-info">
            <p><strong>Error Reference:</strong> <?= date('Y-m-d H:i:s') ?> - <?= $errorCode ?></p>
            <p>If this problem persists, please contact our technical support team.</p>
        </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Auto-redirect after 30 seconds for 404 errors
        <?php if ($errorCode == 404): ?>
        setTimeout(function() {
            window.location.href = '/';
        }, 30000);
        <?php endif; ?>
    </script>
</body>
</html>
