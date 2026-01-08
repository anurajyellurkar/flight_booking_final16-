<?php
require_once '../config.php';

// admin protection
if (
    !isset($_SESSION['user']) ||
    $_SESSION['user']['role'] !== 'admin'
) {
    header("Location: ../login.php");
    exit;
}

// use main modern header
include __DIR__ . '/../partials/header.php';
?>

<h2>All Bookings</h2>

<div class="card">
<table class="table">
<thead>
<tr>
    <th>Ticket</th>
    <th>User</th>
    <th>Passengers</th>
    <th>Total</th>
    <th>Status</th>
    <th>View</th>
</tr>
</thead>

<tbody>

<?php
$q = $conn->query("
    SELECT b.*, u.name AS user_name
    FROM bookings b
    LEFT JOIN users u ON u.id = b.user_id
    ORDER BY b.id DESC
");

while ($b = $q->fetch_assoc()):
    $ps = $conn->query("
        SELECT name, seat_no, cancel_status
        FROM passengers
        WHERE booking_id = {$b['id']}
    ");
?>

<tr>
    <td><strong><?= htmlspecialchars($b['ticket_no']) ?></strong></td>

    <td><?= htmlspecialchars($b['user_name']) ?></td>

    <td>
        <?php while ($p = $ps->fetch_assoc()): ?>
            <?= htmlspecialchars($p['name']) ?>
            (Seat <?= htmlspecialchars($p['seat_no']) ?>)

            <?php if ($p['cancel_status'] !== 'ACTIVE'): ?>
                <span style="
                  background:#ff4d4d;
                  color:white;
                  padding:2px 6px;
                  border-radius:6px;
                  font-size:12px;
                  margin-left:6px;">
                  cancelled
                </span>
            <?php endif; ?>
            <br>
        <?php endwhile; ?>
    </td>

    <td>₹<?= number_format($b['total_price'], 2) ?></td>

    <td>
        <?php if ($b['status'] === 'CONFIRMED'): ?>
            <span style="
              background:#16a34a;
              color:white;
              padding:4px 10px;
              border-radius:999px;
              font-size:12px;">
              CONFIRMED
            </span>
        <?php else: ?>
            <span style="
              background:#475569;
              color:white;
              padding:4px 10px;
              border-radius:999px;
              font-size:12px;">
              <?= htmlspecialchars($b['status']) ?>
            </span>
        <?php endif; ?>
    </td>

    <td>
        <a class="btn"
           href="booking_view.php?id=<?= $b['id'] ?>">
           View
        </a>
    </td>
</tr>

<?php endwhile; ?>

</tbody>
</table>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
