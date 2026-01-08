<?php
require_once '../config.php';
require_once 'auth.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: bookings.php');
    exit;
}

// booking + flight
$stmt = $conn->prepare("
  SELECT b.*, f.flight_no, f.aircraft_type
  FROM bookings b
  LEFT JOIN flights f ON f.id = b.flight_id
  WHERE b.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();
if (!$booking) {
    header('Location: bookings.php');
    exit;
}

// passengers
$ps = $conn->query("SELECT * FROM passengers WHERE booking_id=" . (int)$id);

include 'header.php';
?>
<h2>Booking #<?= htmlspecialchars($booking['id']) ?></h2>

<table class="table table-bordered w-50">
<tr><th>Ticket</th><td><?= htmlspecialchars($booking['ticket_no']) ?></td></tr>
<tr><th>Flight</th><td><?= htmlspecialchars($booking['flight_no']) ?> (<?= htmlspecialchars($booking['aircraft_type']) ?>)</td></tr>
<tr><th>Total</th><td>₹<?= number_format($booking['total_price'],2) ?></td></tr>
<tr><th>Status</th><td><?= htmlspecialchars($booking['status']) ?></td></tr>
</table>

<h4>Passengers</h4>
<table class="table">
<tr><th>Name</th><th>Seat</th><th>Status</th><th>Action</th></tr>
<?php while ($p = $ps->fetch_assoc()): ?>
<tr>
  <td><?= htmlspecialchars($p['name']) ?></td>
  <td><?= htmlspecialchars($p['seat_no']) ?></td>
  <td><?= htmlspecialchars($p['cancel_status']) ?></td>
  <td>
    <?php if ($p['cancel_status'] === 'ACTIVE'): ?>
      <a href="cancel_passenger.php?id=<?= $p['id'] ?>&booking=<?= $booking['id'] ?>" class="btn btn-sm btn-danger"
         onclick="return confirm('Cancel passenger?')">Cancel</a>
    <?php else: ?>
      —
    <?php endif; ?>
  </td>
</tr>
<?php endwhile; ?>
</table>

<?php include 'footer.php'; ?>
