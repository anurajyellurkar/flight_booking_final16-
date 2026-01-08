<?php
require_once 'dashboard_header.php';

// fetch pending refunds
$r = $conn->query("
    SELECT id, ticket_no, refund_status
    FROM bookings
    WHERE refund_status = 'PENDING'
    ORDER BY id DESC
");
?>

<h2>Refund Requests</h2>

<table class="table">
<tr>
  <th>Ticket</th>
  <th>Status</th>
  <th>Action</th>
</tr>

<?php if ($r->num_rows === 0): ?>
<tr>
  <td colspan="3">No pending refunds</td>
</tr>
<?php endif; ?>

<?php while ($x = $r->fetch_assoc()): ?>
<tr>
  <td><?= htmlspecialchars($x['ticket_no']) ?></td>
  <td><?= htmlspecialchars($x['refund_status']) ?></td>
  <td>
    <a href="refund_approve.php?id=<?= $x['id'] ?>"
       onclick="return confirm('Approve refund?')">
       Approve
    </a>
  </td>
</tr>
<?php endwhile; ?>
</table>

</div>
</body>
</html>
