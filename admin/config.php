<?php
/**
 * Production Configuration for Church Admin System
 * 
 * This file contains production-ready configuration settings
 * with proper security measures and environment handling.
 */

// Prevent direct access
if (!defined('ADMIN_ACCESS')) {
    http_response_code(403);
    exit('Access denied');
}

// Environment detection
define('ENVIRONMENT', $_ENV['ENVIRONMENT'] ?? 'production');
define('DEBUG_MODE', ENVIRONMENT === 'development');

// Security Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');

// Database Configuration
class DatabaseConfig {
    public static function getConnection() {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $dbname = $_ENV['DB_NAME'] ?? 'kazrxdvk_church_management';
        $username = $_ENV['DB_USER'] ?? 'kazrxdvk_vincent';
        $password = $_ENV['DB_PASS'] ?? '@Admin@2025';
        
        try {
            $pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4", 
                $username, 
                $password, 
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
                ]
            );
            return $pdo;
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            if (DEBUG_MODE) {
                die("Database connection failed: " . $e->getMessage());
            } else {
                die("Database connection failed. Please contact administrator.");
            }
        }
    }
}

// Security Functions
class Security {
    /**
     * Generate CSRF token
     */
    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Verify CSRF token
     */
    public static function verifyCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Sanitize input
     */
    public static function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Rate limiting for login attempts
     */
    public static function checkRateLimit($identifier, $maxAttempts = 5, $timeWindow = 900) {
        $key = 'login_attempts_' . md5($identifier);
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 0, 'first_attempt' => time()];
        }
        
        $attempts = $_SESSION[$key];
        
        // Reset if time window has passed
        if (time() - $attempts['first_attempt'] > $timeWindow) {
            $_SESSION[$key] = ['count' => 0, 'first_attempt' => time()];
            return true;
        }
        
        return $attempts['count'] < $maxAttempts;
    }
    
    /**
     * Record failed login attempt
     */
    public static function recordFailedAttempt($identifier) {
        $key = 'login_attempts_' . md5($identifier);
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 0, 'first_attempt' => time()];
        }
        
        $_SESSION[$key]['count']++;
    }
    
    /**
     * Clear login attempts on successful login
     */
    public static function clearLoginAttempts($identifier) {
        $key = 'login_attempts_' . md5($identifier);
        unset($_SESSION[$key]);
    }
}

// Logging Functions
class Logger {
    public static function log($message, $level = 'INFO') {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] [$level] $message" . PHP_EOL;
        
        $logFile = __DIR__ . '/logs/admin.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    public static function logSecurity($message, $userId = null) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $fullMessage = "SECURITY: $message | IP: $ip | User: " . ($userId ?? 'anonymous') . " | UA: $userAgent";
        
        self::log($fullMessage, 'SECURITY');
    }
}

// Application Settings
define('APP_NAME', 'Church Admin System');
define('APP_VERSION', '2.0.0');
define('ADMIN_SESSION_TIMEOUT', 3600); // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes

// Set security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

if (isset($_SERVER['HTTPS'])) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}

// Content Security Policy
$csp = "default-src 'self'; " .
       "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://code.jquery.com https://www.googletagmanager.com https://www.google-analytics.com https://cdnjs.cloudflare.com; " .
       "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com https://cdnjs.cloudflare.com; " .
       "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; " .
       "img-src 'self' data: https: blob:; " .
       "connect-src 'self' https://www.google-analytics.com https://analytics.google.com https://fonts.googleapis.com https://fonts.gstatic.com; " .
       "object-src 'none'; base-uri 'self';";

// Temporarily disabled - CSP handled by meta tag in main site
// header("Content-Security-Policy: $csp");
