<?php
require_once 'config.php';
include 'partials/header.php';
?>

<h2>Search Flights</h2>

<form class="card" method="GET" action="search.php">
  <label>From</label><br>
  <input name="origin" required><br><br>

  <label>To</label><br>
  <input name="destination" required><br><br>

  <label>Date</label><br>
  <input type="date" name="date" required><br><br>

  <button type="submit">Search Flights</button>
</form>

<?php include 'partials/footer.php'; ?>
