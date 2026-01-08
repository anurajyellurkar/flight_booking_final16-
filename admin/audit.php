<?php
require_once '../config.php';
require_once 'auth.php';
require_once 'dashboard_header.php';


$q = $conn->query("SELECT a.*, u.name AS user_name
                   FROM audit_log a
                   LEFT JOIN users u ON u.id = a.user_id
                   ORDER BY a.created_at DESC
                   LIMIT 500");

?>
<h2>Audit Log</h2>
<table class="table">
<tr><th>Time</th><th>User</th><th>Role</th><th>Action</th><th>Details</th></tr>
<?php while ($r = $q->fetch_assoc()): ?>
<tr>
  <td><?= htmlspecialchars($r['created_at']) ?></td>
  <td><?= htmlspecialchars($r['user_name'] ?? 'System') ?></td>
  <td><?= htmlspecialchars($r['role']) ?></td>
  <td><?= htmlspecialchars($r['action']) ?></td>
  <td><?= htmlspecialchars($r['details']) ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php include 'footer.php'; ?>
