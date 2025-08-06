-- SQL for volunteers table
CREATE TABLE IF NOT EXISTS volunteers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(30),
    ministry VARCHAR(100),
    password VARCHAR(255) NOT NULL,
    verified TINYINT(1) DEFAULT 0,
    otp VARCHAR(10),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
