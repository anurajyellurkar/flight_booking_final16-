<?php
require_once 'config.php';

$flight_id = (int)$_POST['flight_id'];
$seat = $_POST['seat'];

$stmt = $conn->prepare("
UPDATE seats
SET status='FREE', held_until=NULL
WHERE flight_id=? AND seat_no=? AND status='HELD'
");
$stmt->bind_param("is", $flight_id, $seat);
$stmt->execute();

echo 'ok';
