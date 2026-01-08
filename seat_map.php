<?php
require_once 'config.php';

$flight_id = isset($_GET['flight_id']) ? intval($_GET['flight_id']) : 0;
if ($flight_id <= 0) {
    die('Flight required');
}

// use aircraft_type to determine max seats
$stmt = $conn->prepare("SELECT aircraft_type FROM flights WHERE id = ?");
$stmt->bind_param("i", $flight_id);
$stmt->execute();
$flight = $stmt->get_result()->fetch_assoc();
if (!$flight) die('Invalid flight');

$air = $flight['aircraft_type'] ?? 'A320';
$max = ($air === 'B737') ? 36 : 30;

include 'partials/header.php';
?>
<h2>Seat Map</h2>
<div id="grid"></div>

<script>
const flightId = <?= $flight_id ?>;
const maxSeats = <?= $max ?>;

function refresh() {
  fetch('api_seats.php?flight_id=' + flightId)
    .then(r => r.json())
    .then(data => {
      const g = document.getElementById('grid');
      g.innerHTML = '';
      for (let i = 1; i <= maxSeats; i++) {
        const id = 'S' + i;
        const status = data[id] || 'FREE';
        const el = document.createElement('button');
        el.innerText = id + ' (' + status + ')';
        el.style.margin = '4px';
        el.disabled = (status === 'BOOKED');
        el.className = status.toLowerCase();
        g.appendChild(el);
      }
    });
}
setInterval(() => { fetch('api_cleanup.php'); refresh(); }, 2000);
refresh();
</script>

<?php include 'partials/footer.php'; ?>
