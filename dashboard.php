<?php
require_once 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['user']['id'];

$q = $conn->query("
    SELECT b.*, f.flight_no, f.origin, f.destination, f.depart_time
    FROM bookings b
    JOIN flights f ON f.id = b.flight_id
    WHERE b.user_id = $uid
    ORDER BY b.id DESC
");

include 'partials/header.php';
?>

<h2>My Bookings</h2>

<?php if ($q->num_rows === 0): ?>
  <div class="card">You have no bookings yet.</div>
<?php endif; ?>

<?php while ($b = $q->fetch_assoc()): ?>

<div class="card">

  <h3>🎫 Ticket: <?= htmlspecialchars($b['ticket_no']) ?></h3>

  <p>
    ✈️ <b><?= htmlspecialchars($b['flight_no']) ?></b><br>
    🛫 <?= htmlspecialchars($b['origin']) ?> → <?= htmlspecialchars($b['destination']) ?><br>
    🕒 <?= htmlspecialchars($b['depart_time']) ?>
  </p>

  <h4>Passengers</h4>

  <table class="table">
    <tr>
      <th>Name</th>
      <th>Seat</th>
      <th>Status</th>
      <th>Action</th>
    </tr>

    <?php
    $ps = $conn->query("
        SELECT *
        FROM passengers
        WHERE booking_id = {$b['id']}
    ");
    while ($p = $ps->fetch_assoc()):
    ?>

    <tr>
      <td><?= htmlspecialchars($p['name']) ?></td>
      <td><?= htmlspecialchars($p['seat_no']) ?></td>

      <td>
        <?php if ($p['cancel_status'] === 'ACTIVE'): ?>
          <span style="color:#7CFF7C;font-weight:bold">ACTIVE</span>
        <?php else: ?>
          <span style="color:#FF6B6B;font-weight:bold">CANCELLED By admin</span>
        <?php endif; ?>
      </td>

      <td>
        <?php if ($p['cancel_status'] === 'ACTIVE' && $b['status'] === 'CONFIRMED'): ?>
          <a class="btn"
             style="background:#ff4d4d"
             href="cancel_user.php?id=<?= $p['id'] ?>"
             onclick="return confirm('Cancel ticket for <?= htmlspecialchars($p['name']) ?>?')">
             Cancel
          </a>
        <?php else: ?>
          —
        <?php endif; ?>
      </td>
    </tr>

    <?php endwhile; ?>
  </table>

  <div style="margin-top:12px">
    <a class="btn" href="ticket.php?t=<?= $b['ticket_no'] ?>">
      Download Ticket (PDF)
    </a>
  </div>

</div>

<?php endwhile; ?>

<?php include 'partials/footer.php'; ?>
