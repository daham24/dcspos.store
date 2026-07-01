-- Staff Attendance Feature Migration
-- Run this SQL against the dcs_pos_system database

-- Add unique QR token column to admins table
ALTER TABLE admins ADD COLUMN qr_token VARCHAR(64) UNIQUE NULL AFTER role;

-- Create staff attendance table
CREATE TABLE IF NOT EXISTS staff_attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    check_in_time DATETIME NOT NULL,
    check_in_method ENUM('qr_scan', 'manual') NOT NULL DEFAULT 'qr_scan',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_daily_checkin (admin_id, attendance_date),
    INDEX idx_attendance_date (attendance_date),
    INDEX idx_admin_id (admin_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Generate QR tokens for existing staff members who don't have one
-- (Run via application on first staff edit, or use PHP script)
