CREATE DATABASE IF NOT EXISTS flight_booking;
USE flight_booking;

-- =========================
-- DROP OLD TABLES (SAFE)
-- =========================
DROP TABLE IF EXISTS audit_log;
DROP TABLE IF EXISTS seats;
DROP TABLE IF EXISTS passengers;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS flights;
DROP TABLE IF EXISTS users;

-- =========================
-- USERS
-- =========================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80),
    email VARCHAR(120) UNIQUE,
    password VARCHAR(255),
    role ENUM('user','admin') DEFAULT 'user'
);

-- =========================
-- FLIGHTS
-- =========================
CREATE TABLE flights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    flight_no VARCHAR(40),
    origin VARCHAR(80),
    destination VARCHAR(80),
    depart_time DATETIME,
    arrive_time DATETIME,
    aircraft_type VARCHAR(20),
    price_economy DECIMAL(10,2),
    price_business DECIMAL(10,2),
    total_seats INT DEFAULT 30
);

-- =========================
-- BOOKINGS
-- =========================
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    flight_id INT,
    class_type ENUM('ECONOMY','BUSINESS'),
    total_price DECIMAL(10,2),
    ticket_no VARCHAR(40),
    status ENUM('CONFIRMED','CANCELLED') DEFAULT 'CONFIRMED',
    refund_status ENUM('NONE','PENDING','REFUNDED') DEFAULT 'NONE',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- PASSENGERS
-- =========================
CREATE TABLE passengers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT,
    name VARCHAR(80),
    age INT,
    gender VARCHAR(10),
    phone VARCHAR(15),
    seat_no VARCHAR(10),
    cancel_status ENUM('ACTIVE','CANCELLED_ADMIN','CANCELLED_USER') DEFAULT 'ACTIVE',
    refund_status ENUM('NONE','PENDING','REFUNDED') DEFAULT 'NONE'
);

-- =========================
-- SEATS
-- =========================
CREATE TABLE seats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    flight_id INT,
    seat_no VARCHAR(10),
    status ENUM('FREE','HELD','BOOKED') DEFAULT 'FREE',
    held_until DATETIME DEFAULT NULL
);

-- =========================
-- AUDIT LOG
-- =========================
CREATE TABLE audit_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    role VARCHAR(20),
    action VARCHAR(100),
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- DEFAULT ADMIN USER
-- =========================
INSERT INTO users (name,email,password,role)
VALUES ('Admin','admin@example.com',MD5('admin123'),'admin');
