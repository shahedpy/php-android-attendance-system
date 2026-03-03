-- ============================================================
-- Android Attendance System - Database Schema
-- Import: mysql -u root < database.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS aas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE aas;

-- Users (authentication)
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('admin','parent','teacher') NOT NULL DEFAULT 'admin',
    active     TINYINT(1)   NOT NULL DEFAULT 1,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Classes
CREATE TABLE IF NOT EXISTS classes (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Teachers
CREATE TABLE IF NOT EXISTS teachers (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    email      VARCHAR(100) DEFAULT NULL,
    phone      VARCHAR(20)  DEFAULT NULL,
    class_id   INT          DEFAULT NULL,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Students
CREATE TABLE IF NOT EXISTS students (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    name             VARCHAR(100) NOT NULL,
    roll_number      VARCHAR(50)  NOT NULL,
    class_id         INT          NOT NULL,
    parent_username  VARCHAR(50)  DEFAULT NULL,
    created_at       TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Attendance
CREATE TABLE IF NOT EXISTS attendance (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT  NOT NULL,
    class_id   INT  NOT NULL,
    date       DATE NOT NULL,
    status     ENUM('present','absent','late') NOT NULL DEFAULT 'present',
    marked_by  VARCHAR(50) DEFAULT NULL,
    created_at TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id)   REFERENCES classes(id)  ON DELETE CASCADE,
    UNIQUE KEY unique_attendance (student_id, date)
) ENGINE=InnoDB;

-- Feedback
CREATE TABLE IF NOT EXISTS feedback (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    parent_username VARCHAR(50)  NOT NULL,
    subject         VARCHAR(200) NOT NULL,
    message         TEXT         NOT NULL,
    created_at      TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Default data  (passwords are MD5-hashed)
--   admin    / admin123
--   parent1  / parent123
--   teacher1 / teacher123
-- ---------------------------------------------------------
INSERT INTO users (username, password, role, active) VALUES
('admin',    MD5('admin123'),   'admin',   1),
('parent1',  MD5('parent123'),  'parent',  1),
('teacher1', MD5('teacher123'), 'teacher', 1);

INSERT INTO classes (name) VALUES ('Class 1'), ('Class 2');

INSERT INTO teachers (name, username, email, phone, class_id) VALUES
('John Smith', 'teacher1', 'teacher1@example.com', '1234567890', 1);

INSERT INTO students (name, roll_number, class_id, parent_username) VALUES
('Alice Johnson',  '001', 1, 'parent1'),
('Bob Williams',   '002', 1, 'parent1'),
('Carol Davis',    '003', 2, NULL);
