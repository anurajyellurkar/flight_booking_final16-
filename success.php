<?php
require_once 'config.php';
if (!isset($_SESSION['last_ticket'])) {
    header("Location: dashboard.php");
    exit;
}
$ticket=$_SESSION['last_ticket'];
include 'partials/header.php';
?>
<div class="card">
<h3>Booking Confirmed ✔</h3>
Ticket: <b><?=$ticket?></b><br><br>
<a href="ticket.php?t=<?=$ticket?>">Download Ticket</a>
</div>
<?php include 'partials/footer.php'; ?>
