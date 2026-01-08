<?php
require_once '../config.php';
require_once '../lib/audit.php';
require_once 'auth.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: passenger_refunds.php');
    exit;
}

// set refund_status for passenger
$stmt = $conn->prepare("UPDATE passengers SET refund_status='REFUNDED' WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// audit
log_action($conn, $_SESSION['user']['id'], 'ADMIN', 'PASSENGER_REFUND', "Passenger refund approved id={$id}");

header("Location: passenger_refunds.php");
exit;
