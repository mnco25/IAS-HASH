-- Create database
CREATE DATABASE
IF NOT EXISTS user_auth_system;
USE user_auth_system;

-- Create users table with role field
CREATE TABLE
IF NOT EXISTS users
(
    id INT
(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR
(100) NOT NULL,
    email VARCHAR
(150) UNIQUE NOT NULL,
    password VARCHAR
(255) NOT NULL,
    role ENUM
('admin', 'user', 'guest') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
UPDATE CURRENT_TIMESTAMP
);

-- Insert sample admin user (password: admin123)
INSERT INTO users
    (name, email, password, role)
VALUES
    ('System Administrator', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Create a simple content management table for role-based content
CREATE TABLE
IF NOT EXISTS dashboard_content
(
    id INT
(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR
(255) NOT NULL,
    content TEXT NOT NULL,
    allowed_roles
SET
('admin', 'user', 'guest') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample dashboard content
INSERT INTO dashboard_content
    (title, content, allowed_roles)
VALUES
    ('Admin Panel', 'Access to user management, system settings, and administrative tools.', 'admin'),
    ('User Statistics', 'View your personal statistics and account information.', 'admin,user'),
    ('Welcome Message', 'Welcome to our secure authentication system!', 'admin,user,guest'),
    ('Guest Information', 'Limited access area for guest users.', 'guest'),
    ('Advanced Features', 'Access to premium features and advanced tools.', 'admin,user');
