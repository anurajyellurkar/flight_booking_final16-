<?php
require_once '../config.php';
require_once '../lib/audit.php';
require_once 'auth.php';

$id = (int)$_GET['id'];

$conn->query("
UPDATE bookings
SET refund_status='REFUNDED'
WHERE id=$id AND refund_status='PENDING'
");

log_action(
    $conn,
    $_SESSION['user']['id'],
    'ADMIN',
    'REFUND_APPROVED',
    "Booking $id refunded"
);

header("Location: refunds.php");
exit;
