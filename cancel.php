<?php
require_once 'config.php';
require_once 'lib/audit.php';

if (!isset($_SESSION['user'])) die("Login required");

$tk=$_GET['t'] ?? '';
$q=$conn->prepare("SELECT id FROM bookings WHERE ticket_no=? AND user_id=?");
$q->bind_param("si",$tk,$_SESSION['user']['id']);
$q->execute();
$b=$q->get_result()->fetch_assoc();

if(!$b) die("Invalid ticket");

$conn->query("
UPDATE bookings
SET status='CANCELLED', refund_status='PENDING'
WHERE id={$b['id']}
");

log_action($conn,$_SESSION['user']['id'],'USER','BOOKING_CANCELLED',$tk);
header("Location: dashboard.php");
exit;
