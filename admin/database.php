<?php
/**
 * Database Management for Church Admin System
 * 
 * Handles database initialization, migrations, and schema management
 * for production environment.
 */

// Prevent direct access
if (!defined('ADMIN_ACCESS')) {
    http_response_code(403);
    exit('Access denied');
}

class DatabaseManager {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Initialize database tables with proper indexes and constraints
     */
    public function initializeTables() {
        $tables = [
            'admins' => "
                CREATE TABLE IF NOT EXISTS admins (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(255) UNIQUE NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    email VARCHAR(255) UNIQUE,
                    full_name VARCHAR(255),
                    role ENUM('super_admin', 'admin', 'editor') DEFAULT 'admin',
                    last_login TIMESTAMP NULL,
                    failed_login_attempts INT DEFAULT 0,
                    locked_until TIMESTAMP NULL,
                    is_active BOOLEAN DEFAULT TRUE,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    
                    INDEX idx_username (username),
                    INDEX idx_email (email),
                    INDEX idx_is_active (is_active)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ",
            
            'sermons' => "
                CREATE TABLE IF NOT EXISTS sermons (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    title VARCHAR(500) NOT NULL,
                    date DATE NOT NULL,
                    speaker VARCHAR(255) NOT NULL,
                    content TEXT NOT NULL,
                    scripture_reference VARCHAR(255),
                    series VARCHAR(255),
                    audio_url VARCHAR(500),
                    video_url VARCHAR(500),
                    notes_url VARCHAR(500),
                    is_published BOOLEAN DEFAULT FALSE,
                    view_count INT DEFAULT 0,
                    created_by INT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    
                    INDEX idx_date (date),
                    INDEX idx_speaker (speaker),
                    INDEX idx_is_published (is_published),
                    INDEX idx_series (series),
                    FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE SET NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ",
            
            'members' => "
                CREATE TABLE IF NOT EXISTS members (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    member_id VARCHAR(50) UNIQUE,
                    first_name VARCHAR(255) NOT NULL,
                    last_name VARCHAR(255) NOT NULL,
                    email VARCHAR(255) UNIQUE,
                    phone VARCHAR(50),
                    date_of_birth DATE,
                    gender ENUM('male', 'female', 'other'),
                    address TEXT,
                    city VARCHAR(100),
                    state VARCHAR(100),
                    postal_code VARCHAR(20),
                    country VARCHAR(100) DEFAULT 'Kenya',
                    joined_date DATE,
                    membership_type ENUM('member', 'visitor', 'regular_attendee') DEFAULT 'visitor',
                    status ENUM('active', 'inactive', 'transferred') DEFAULT 'active',
                    emergency_contact_name VARCHAR(255),
                    emergency_contact_phone VARCHAR(50),
                    baptized BOOLEAN DEFAULT FALSE,
                    baptism_date DATE NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    
                    INDEX idx_member_id (member_id),
                    INDEX idx_email (email),
                    INDEX idx_phone (phone),
                    INDEX idx_status (status),
                    INDEX idx_membership_type (membership_type),
                    INDEX idx_name (last_name, first_name)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ",
            
            'events' => "
                CREATE TABLE IF NOT EXISTS events (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    title VARCHAR(255) NOT NULL,
                    description TEXT,
                    event_date DATE NOT NULL,
                    event_time TIME,
                    end_date DATE,
                    end_time TIME,
                    location VARCHAR(255),
                    address TEXT,
                    event_type ENUM('service', 'meeting', 'conference', 'social', 'outreach', 'other') DEFAULT 'other',
                    max_attendees INT NULL,
                    registration_required BOOLEAN DEFAULT FALSE,
                    registration_deadline DATE NULL,
                    contact_person VARCHAR(255),
                    contact_email VARCHAR(255),
                    contact_phone VARCHAR(50),
                    status ENUM('upcoming', 'ongoing', 'completed', 'cancelled') DEFAULT 'upcoming',
                    is_public BOOLEAN DEFAULT TRUE,
                    created_by INT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    
                    INDEX idx_event_date (event_date),
                    INDEX idx_status (status),
                    INDEX idx_event_type (event_type),
                    INDEX idx_is_public (is_public),
                    FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE SET NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ",
            
            'volunteers' => "
                CREATE TABLE IF NOT EXISTS volunteers (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    member_id INT,
                    first_name VARCHAR(255) NOT NULL,
                    last_name VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL,
                    phone VARCHAR(50),
                    ministry VARCHAR(255),
                    skills TEXT,
                    availability TEXT,
                    commitment_level ENUM('weekly', 'monthly', 'occasional') DEFAULT 'occasional',
                    background_check BOOLEAN DEFAULT FALSE,
                    training_completed BOOLEAN DEFAULT FALSE,
                    start_date DATE,
                    status ENUM('active', 'inactive', 'pending') DEFAULT 'pending',
                    notes TEXT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    
                    INDEX idx_email (email),
                    INDEX idx_ministry (ministry),
                    INDEX idx_status (status),
                    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ",
            
            'admin_logs' => "
                CREATE TABLE IF NOT EXISTS admin_logs (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    admin_id INT,
                    action VARCHAR(255) NOT NULL,
                    table_name VARCHAR(100),
                    record_id INT,
                    old_values JSON,
                    new_values JSON,
                    ip_address VARCHAR(45),
                    user_agent TEXT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    
                    INDEX idx_admin_id (admin_id),
                    INDEX idx_action (action),
                    INDEX idx_table_name (table_name),
                    INDEX idx_created_at (created_at),
                    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            "
        ];
        
        foreach ($tables as $tableName => $sql) {
            try {
                $this->pdo->exec($sql);
                Logger::log("Table '$tableName' created/verified successfully");
            } catch (PDOException $e) {
                Logger::log("Error creating table '$tableName': " . $e->getMessage(), 'ERROR');
                throw $e;
            }
        }
    }
    
    /**
     * Create default admin user (only in development or if no admins exist)
     */
    public function createDefaultAdmin() {
        $adminCount = $this->pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
        
        if ($adminCount == 0) {
            $defaultUsername = $_ENV['DEFAULT_ADMIN_USER'] ?? 'admin';
            $defaultPassword = $_ENV['DEFAULT_ADMIN_PASS'] ?? 'admin123';
            $defaultEmail = $_ENV['DEFAULT_ADMIN_EMAIL'] ?? 'admin@church.local';
            
            $hashedPassword = password_hash($defaultPassword, PASSWORD_DEFAULT);
            
            $stmt = $this->pdo->prepare("
                INSERT INTO admins (username, password, email, full_name, role) 
                VALUES (?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $defaultUsername,
                $hashedPassword,
                $defaultEmail,
                'System Administrator',
                'super_admin'
            ]);
            
            Logger::logSecurity("Default admin account created: $defaultUsername");
            
            if (ENVIRONMENT === 'production') {
                Logger::log("WARNING: Default admin created in production. Please change credentials immediately!", 'WARNING');
            }
        }
    }
    
    /**
     * Run database migrations
     */
    public function runMigrations() {
        // Add any schema updates here
        $migrations = [
            '2024_01_01_add_admin_security_fields' => "
                ALTER TABLE admins 
                ADD COLUMN IF NOT EXISTS failed_login_attempts INT DEFAULT 0,
                ADD COLUMN IF NOT EXISTS locked_until TIMESTAMP NULL,
                ADD COLUMN IF NOT EXISTS last_login TIMESTAMP NULL
            ",
            
            '2024_01_02_add_sermon_fields' => "
                ALTER TABLE sermons 
                ADD COLUMN IF NOT EXISTS scripture_reference VARCHAR(255),
                ADD COLUMN IF NOT EXISTS series VARCHAR(255),
                ADD COLUMN IF NOT EXISTS is_published BOOLEAN DEFAULT FALSE
            "
        ];
        
        foreach ($migrations as $name => $sql) {
            try {
                $this->pdo->exec($sql);
                Logger::log("Migration '$name' completed successfully");
            } catch (PDOException $e) {
                // Log but don't fail if column already exists
                if (strpos($e->getMessage(), 'Duplicate column name') === false) {
                    Logger::log("Migration '$name' failed: " . $e->getMessage(), 'ERROR');
                }
            }
        }
    }
    
    /**
     * Optimize database tables
     */
    public function optimizeTables() {
        $tables = ['admins', 'sermons', 'members', 'events', 'volunteers', 'admin_logs'];
        
        foreach ($tables as $table) {
            try {
                $this->pdo->exec("OPTIMIZE TABLE $table");
                Logger::log("Table '$table' optimized successfully");
            } catch (PDOException $e) {
                Logger::log("Error optimizing table '$table': " . $e->getMessage(), 'WARNING');
            }
        }
    }
    
    /**
     * Get database statistics
     */
    public function getDatabaseStats() {
        $stats = [];
        $tables = ['admins', 'sermons', 'members', 'events', 'volunteers'];
        
        foreach ($tables as $table) {
            try {
                $count = $this->pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
                $stats[$table] = $count;
            } catch (PDOException $e) {
                $stats[$table] = 0;
            }
        }
        
        return $stats;
    }
}
