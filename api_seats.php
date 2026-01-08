<?php
require_once 'config.php';

$flight_id = (int)($_GET['flight_id'] ?? 0);
$data = [];

$q = $conn->prepare("
    SELECT seat_no, status
    FROM seats
    WHERE flight_id = ?
");
$q->bind_param("i", $flight_id);
$q->execute();
$r = $q->get_result();

while ($row = $r->fetch_assoc()) {
    $data[$row['seat_no']] = $row['status'];
}

header('Content-Type: application/json');
echo json_encode($data);
