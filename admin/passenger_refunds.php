<?php
require_once '../config.php';
require_once 'auth.php';
include 'header.php';

$q = $conn->query("
  SELECT p.id, p.name, p.seat_no, p.refund_status, b.ticket_no
  FROM passengers p
  JOIN bookings b ON b.id = p.booking_id
  WHERE p.refund_status = 'PENDING'
  ORDER BY p.id DESC
");
?>
<h2>Passenger Refund Requests</h2>
<table class="table">
<tr><th>Passenger</th><th>Seat</th><th>Ticket</th><th>Status</th><th>Action</th></tr>
<?php while ($r = $q->fetch_assoc()): ?>
<tr>
  <td><?= htmlspecialchars($r['name']) ?></td>
  <td><?= htmlspecialchars($r['seat_no']) ?></td>
  <td><?= htmlspecialchars($r['ticket_no']) ?></td>
  <td><?= htmlspecialchars($r['refund_status']) ?></td>
  <td><a href="passenger_refund_approve.php?id=<?= $r['id'] ?>">Approve</a></td>
</tr>
<?php endwhile; ?>
</table>
<?php include 'footer.php'; ?>
