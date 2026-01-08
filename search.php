<?php
require_once 'config.php';
include 'partials/header.php';

// Read GET parameters safely
$origin = $_GET['origin'] ?? '';
$destination = $_GET['destination'] ?? '';
$date = $_GET['date'] ?? '';

// Validate inputs
if ($origin === '' || $destination === '' || $date === '') {
    echo "<div class='card'>Invalid search parameters</div>";
    include 'partials/footer.php';
    exit;
}

// Prepare search query
$sql = "
    SELECT *
    FROM flights
    WHERE origin LIKE ?
      AND destination LIKE ?
      AND DATE(depart_time) = ?
    ORDER BY depart_time
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "<div class='card'>Query prepare failed: " . htmlspecialchars($conn->error) . "</div>";
    include 'partials/footer.php';
    exit;
}

$o = "%{$origin}%";
$d = "%{$destination}%";

$stmt->bind_param("sss", $o, $d, $date);
$stmt->execute();

$result = $stmt->get_result();
?>

<h2>Available Flights</h2>

<?php if ($result->num_rows === 0): ?>
    <div class="card">No flights found</div>
<?php else: ?>
<div class="card">
<table class="table">
<tr>
    <th>Flight No</th>
    <th>Route</th>
    <th>Departure</th>
    <th>Economy</th>
    <th>Business</th>
    <th>Action</th>
</tr>

<?php while ($f = $result->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($f['flight_no']) ?></td>
    <td><?= htmlspecialchars($f['origin']) ?> → <?= htmlspecialchars($f['destination']) ?></td>
    <td><?= htmlspecialchars($f['depart_time']) ?></td>
    <td>₹<?= htmlspecialchars($f['price_economy']) ?></td>
    <td>₹<?= htmlspecialchars($f['price_business']) ?></td>
    <td>
        <a class="btn" href="step_passengers.php?id=<?= $f['id'] ?>">Book</a>
    </td>
</tr>
<?php endwhile; ?>

</table>
</div>
<?php endif; ?>

<?php include 'partials/footer.php'; ?>
