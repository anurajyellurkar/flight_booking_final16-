<?php
require_once '../config.php';
require_once 'auth.php';
include 'header.php';

$r = $conn->query("
SELECT f.flight_no,
 SUM(se.status='BOOKED') AS booked,
 SUM(se.status='FREE') AS free_s,
 SUM(se.status='HELD') AS held
FROM flights f
LEFT JOIN seats se ON se.flight_id = f.id
GROUP BY f.id
");

?>
<h2>Seat Stats</h2>
<table class="table">
<tr><th>Flight</th><th>Booked</th><th>Free</th><th>Held</th></tr>
<?php while($x = $r->fetch_assoc()): ?>
<tr>
  <td><?= htmlspecialchars($x['flight_no']) ?></td>
  <td><?= (int)$x['booked'] ?></td>
  <td><?= (int)$x['free_s'] ?></td>
  <td><?= (int)$x['held'] ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php include 'footer.php'; ?>
