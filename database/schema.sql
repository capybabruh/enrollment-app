-- database/schema.sql
-- Mini Course Enrollment DB App - Lab05

CREATE DATABASE IF NOT EXISTS web_php_lab05_enrollment
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE web_php_lab05_enrollment;

-- Bảng users: tài khoản quản trị / nhân viên tư vấn
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin', 'staff') NOT NULL DEFAULT 'staff',
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng students: học viên đăng ký tư vấn / tiềm năng
CREATE TABLE students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(30),
  status VARCHAR(30) NOT NULL DEFAULT 'new',
  note TEXT,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL,
  deleted_at DATETIME NULL,
  UNIQUE KEY unique_student_email (email),
  INDEX idx_students_created_at (created_at),
  INDEX idx_students_status_created_at (status, created_at),
  INDEX idx_students_phone (phone)
);

-- Bảng enrollments: đăng ký khóa học / hóa đơn học phí
CREATE TABLE enrollments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  enrollment_code VARCHAR(50) NOT NULL,
  student_name VARCHAR(100) NOT NULL,
  student_email VARCHAR(150),
  course_name VARCHAR(150) NOT NULL,
  fee_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
  status VARCHAR(30) NOT NULL DEFAULT 'pending',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL,
  deleted_at DATETIME NULL,
  UNIQUE KEY unique_enrollment_code (enrollment_code),
  INDEX idx_enrollments_created_at (created_at),
  INDEX idx_enrollments_status_created_at (status, created_at),
  INDEX idx_enrollments_student_email (student_email)
);
