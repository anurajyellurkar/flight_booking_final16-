<?php
require_once __DIR__ . '/bootstrap.php';

// Database configuration
$host = "db";
$user = "flightuser";
$pass = "flightpass";
$db   = "flight_booking";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}
