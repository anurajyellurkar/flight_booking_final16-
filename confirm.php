<?php
require_once 'config.php';

if (!isset($_SESSION['user'], $_SESSION['tmp'], $_SESSION['seats'])) {
    header("Location: index.php");
    exit;
}

$data = $_SESSION['tmp'];
$seats = $_SESSION['seats'];

$fid   = (int)$_POST['flight_id'];
$class = $_POST['class'];
$total = $_POST['total'];
$uid   = $_SESSION['user']['id'];

$ticket = 'TKT-'.date('Ymd').'-'.rand(10000,99999);

$b = $conn->prepare("
INSERT INTO bookings
(user_id, flight_id, class_type, total_price, ticket_no)
VALUES (?,?,?,?,?)
");
$b->bind_param("iisss", $uid, $fid, $class, $total, $ticket);
$b->execute();
$bid = $b->insert_id;

$p = $conn->prepare("
INSERT INTO passengers
(booking_id, name, age, gender, seat_no, phone)
VALUES (?,?,?,?,?,?)
");

for ($i=0; $i<count($data['name']); $i++) {
    $p->bind_param(
        "isisss",
        $bid,
        $data['name'][$i],
        $data['age'][$i],
        $data['gender'][$i],
        $seats[$i],
        $data['phone'][$i]
    );
    $p->execute();

    $conn->query("
        UPDATE seats SET status='BOOKED'
        WHERE flight_id=$fid AND seat_no='{$seats[$i]}'
    ");
}

$_SESSION['last_ticket'] = $ticket;
header("Location: success.php");
exit;
