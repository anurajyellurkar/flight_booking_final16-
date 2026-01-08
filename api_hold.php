<?php
require_once 'config.php';

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    exit;
}

$flight_id = (int)$_POST['flight_id'];
$seat = $_POST['seat'];

$stmt = $conn->prepare("
UPDATE seats
SET status='HELD', held_until=DATE_ADD(NOW(), INTERVAL 2 MINUTE)
WHERE flight_id=? AND seat_no=? AND status='FREE'
");
$stmt->bind_param("is", $flight_id, $seat);
$stmt->execute();

echo $stmt->affected_rows > 0 ? 'ok' : 'fail';
