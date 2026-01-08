<?php
require_once 'config.php';
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$fid = (int)($_GET['id'] ?? 0);
include 'partials/header.php';
?>
<h2>Step 1 — Passengers & Class</h2>
<form class="card" method="POST" action="step_details.php">
<input type="hidden" name="flight_id" value="<?=$fid?>">
Passengers (max 6)<br>
<input type="number" name="count" min="1" max="6" required><br><br>

Class<br>
<select name="class">
  <option value="ECONOMY">Economy</option>
  <option value="BUSINESS">Business</option>
</select><br><br>

<button>Continue</button>
</form>
<?php include 'partials/footer.php'; ?>
