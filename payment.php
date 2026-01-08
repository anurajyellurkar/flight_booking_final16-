<?php
require_once 'config.php';
include 'partials/header.php';

$fid   = $_POST['flight_id'];
$class = $_POST['class'];
$c     = $_POST['count'];
$seats = $_POST['seat'] ?? [];

if (count($seats) != $c) {
    echo "<div class='card'>Please select exactly $c seats.</div>";
    include 'partials/footer.php';
    exit;
}

$_SESSION['seats'] = $seats;
$data = $_SESSION['tmp'];

$f = $conn->query("SELECT * FROM flights WHERE id=$fid")->fetch_assoc();
$price = ($class === 'BUSINESS') ? $f['price_business'] : $f['price_economy'];
$total = $price * $c;
?>

<div class="wizard">
  <div>Passengers</div>
  <div>Details</div>
  <div>Seats</div>
  <div><b>Payment</b></div>
  <div>Done</div>
</div>

<h2>Payment</h2>

<div class="card">

<h3>✈ Flight Summary</h3>
<p>
<b><?= htmlspecialchars($f['flight_no']) ?></b><br>
<?= htmlspecialchars($f['origin']) ?> → <?= htmlspecialchars($f['destination']) ?><br>
Departure: <?= htmlspecialchars($f['depart_time']) ?><br>
Class: <b><?= $class ?></b>
</p>

<hr>

<h3>👥 Passengers & Seats</h3>

<table class="table">
<tr>
  <th>Passenger</th>
  <th>Seat</th>
</tr>
<?php for ($i=0; $i<$c; $i++): ?>
<tr>
  <td><?= htmlspecialchars($data['name'][$i]) ?></td>
  <td><?= htmlspecialchars($seats[$i]) ?></td>
</tr>
<?php endfor; ?>
</table>

<div class="total-box">
Total Payable: <b>₹<?= $total ?></b>
</div>

<hr>

<form method="POST" action="confirm.php">

<input type="hidden" name="flight_id" value="<?= $fid ?>">
<input type="hidden" name="class" value="<?= $class ?>">
<input type="hidden" name="total" value="<?= $total ?>">

<h3>💳 Payment Method</h3>

<div class="pay-method">
<select id="ptype" name="payment" required>
  <option value="CARD">Credit / Debit Card</option>
  <option value="UPI">UPI</option>
</select>
</div>

<div id="cardBox">
<label>Card Holder Name</label>
<input name="card_name">

<label>Card Number</label>
<input name="card_no">

<label>Expiry Date</label>
<input name="exp">

<label>CVV</label>
<input name="cvv">
</div>

<div id="upiBox" style="display:none">
<label>UPI ID</label>
<input name="upi">
</div>

<br>

<a class="btn" href="step_seats.php">← Back</a>
<button class="btn">Confirm Payment</button>

</form>
</div>

<script>
const pt = document.getElementById('ptype');
const card = document.getElementById('cardBox');
const upi = document.getElementById('upiBox');

pt.addEventListener('change', () => {
  if (pt.value === 'CARD') {
    card.style.display = 'block';
    upi.style.display = 'none';
  } else {
    card.style.display = 'none';
    upi.style.display = 'block';
  }
});
</script>

<?php include 'partials/footer.php'; ?>
