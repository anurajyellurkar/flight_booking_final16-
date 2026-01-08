<?php
require_once '../config.php';
require_once '../lib/audit.php';
require_once 'auth.php';

$pid = isset($_GET['id']) ? intval($_GET['id']) : 0;
$booking_id = isset($_GET['booking']) ? intval($_GET['booking']) : 0;

if ($pid <= 0) {
    header('Location: bookings.php');
    exit;
}

// fetch passenger with booking
$stmt = $conn->prepare("
  SELECT p.*, b.flight_id
  FROM passengers p
  JOIN bookings b ON b.id = p.booking_id
  WHERE p.id = ?
");
$stmt->bind_param("i", $pid);
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();
if (!$p) {
    header('Location: bookings.php');
    exit;
}

// update passenger cancel + refund status
$u = $conn->prepare("
  UPDATE passengers
  SET cancel_status='CANCELLED_ADMIN', refund_status='PENDING'
  WHERE id = ?
");
$u->bind_param("i", $pid);
$u->execute();

// free seat in seats table
$free = $conn->prepare("
  UPDATE seats
  SET status='FREE', held_until = NULL
  WHERE flight_id = ? AND seat_no = ? AND status IN ('HELD','BOOKED')
");
$free->bind_param("is", $p['flight_id'], $p['seat_no']);
$free->execute();

// audit
log_action($conn, $_SESSION['user']['id'], 'ADMIN', 'CANCEL_PASSENGER',
    "Passenger {$p['name']} (Seat {$p['seat_no']}) cancelled by admin");

header("Location: booking_view.php?id=" . ($booking_id ?: $p['booking_id']));
exit;
