<?php
require_once 'dashboard_header.php';
require_once '../helpers_seat.php'; // for initSeatsForFlight()

$error = '';
$success = '';

// HANDLE ADD FLIGHT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $conn->prepare("
        INSERT INTO flights
        (aircraft_type, flight_no, origin, destination,
         depart_time, arrive_time, price_economy, price_business, total_seats)
        VALUES (?,?,?,?,?,?,?,?,?)
    ");

    if (!$stmt) {
        $error = "Prepare failed: " . $conn->error;
    } else {
        $stmt->bind_param(
            "ssssssddi",
            $_POST['aircraft_type'],
            $_POST['flight_no'],
            $_POST['origin'],
            $_POST['destination'],
            $_POST['depart_time'],
            $_POST['arrive_time'],
            $_POST['price_economy'],
            $_POST['price_business'],
            $_POST['total_seats']
        );

        if ($stmt->execute()) {
            $flight_id = $stmt->insert_id;

            // 🔑 AUTO CREATE SEATS
            initSeatsForFlight(
                $conn,
                $flight_id,
                $_POST['aircraft_type']
            );

            $success = "Flight added successfully";
        } else {
            $error = "Insert failed: " . $stmt->error;
        }
    }
}

// DELETE FLIGHT
if (isset($_GET['del'])) {
    $conn->query("DELETE FROM flights WHERE id=".(int)$_GET['del']);
}

$flights = $conn->query("SELECT * FROM flights ORDER BY id DESC");
?>

<?php if ($error): ?>
<div class="card" style="color:#ff6b6b"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
<div class="card" style="color:#7CFF7C"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="card">
<h3>Add Flight</h3>

<form method="POST">

Aircraft<br>
<select name="aircraft_type" required>
  <option value="A320">A320</option>
  <option value="B737">B737</option>
</select><br><br>

Flight No<br>
<input name="flight_no" required><br><br>

From<br>
<input name="origin" required>

To<br>
<input name="destination" required><br><br>

Depart<br>
<input type="datetime-local" name="depart_time" required>

Arrive<br>
<input type="datetime-local" name="arrive_time" required><br><br>

Economy Price<br>
<input type="number" step="0.01" name="price_economy" required>

Business Price<br>
<input type="number" step="0.01" name="price_business" required><br><br>

Total Seats<br>
<input type="number" name="total_seats" value="30" required><br><br>

<button type="submit">Add Flight</button>
</form>
</div>

<div class="card">
<h3>Flights</h3>
<table class="table">
<tr>
<th>No</th><th>Route</th><th>Economy</th><th>Business</th><th>Seats</th><th>Delete</th>
</tr>

<?php if ($flights->num_rows === 0): ?>
<tr><td colspan="6">No flights added yet</td></tr>
<?php endif; ?>

<?php while ($f = $flights->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($f['flight_no']) ?></td>
<td><?= htmlspecialchars($f['origin']) ?> → <?= htmlspecialchars($f['destination']) ?></td>
<td>₹<?= $f['price_economy'] ?></td>
<td>₹<?= $f['price_business'] ?></td>
<td><?= $f['total_seats'] ?></td>
<td><a href="?del=<?= $f['id'] ?>">Delete</a></td>
</tr>
<?php endwhile; ?>
</table>
</div>

</div>
</body>
</html>
