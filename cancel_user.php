<?php
require_once 'config.php';
require_once 'lib/audit.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$pid = (int)$_GET['id'];

$p = $conn->query("
SELECT p.*, b.user_id, b.flight_id
FROM passengers p
JOIN bookings b ON b.id = p.booking_id
WHERE p.id=$pid
")->fetch_assoc();

if (!$p || $p['user_id'] != $_SESSION['user']['id']) {
    die("Unauthorized");
}

$conn->query("
UPDATE passengers
SET cancel_status='CANCELLED_USER', refund_status='PENDING'
WHERE id=$pid
");

$conn->query("
UPDATE seats
SET status='FREE', held_until=NULL
WHERE flight_id={$p['flight_id']} AND seat_no='{$p['seat_no']}'
");

log_action(
  $conn,
  $_SESSION['user']['id'],
  'USER',
  'CANCEL_PASSENGER',
  "Passenger {$p['name']} cancelled"
);

header("Location: dashboard.php");
exit;
