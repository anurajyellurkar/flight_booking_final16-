<?php
require_once 'config.php'; 
include 'partials/header.php';
include 'helpers.php';

$_SESSION['tmp'] = $_POST;

$fid   = $_POST['flight_id'];
$class = $_POST['class'];
$c     = $_POST['count'];

$taken = takenSeats($conn, $fid);
$flight = $conn->query("SELECT aircraft_type FROM flights WHERE id=$fid")->fetch_assoc();
$type = $flight['aircraft_type'];
?>

<div class="wizard">
  <div>Passengers</div>
  <div>Details</div>
  <div><b>Seats</b></div>
  <div>Payment</div>
  <div>Done</div>
</div>

<h2>Seat Selection <span style="color:#9aa8d1;font-size:14px">(<?= $class ?>)</span></h2>

<div class="card seat-area">

<div class="seat-legend">
  <span><div class="legend-box legend-free"></div> Free</span>
  <span><div class="legend-box legend-selected"></div> Selected</span>
  <span><div class="legend-box legend-taken"></div> Taken</span>
</div>

<form method="POST" action="payment.php">
<input type="hidden" name="flight_id" value="<?= $fid ?>">
<input type="hidden" name="class" value="<?= $class ?>">
<input type="hidden" name="count" value="<?= $c ?>">

<?php if ($class == 'BUSINESS'): ?>
<h3>Business Class</h3>

<div class="seat-grid">
<?php for ($s = 1; $s <= 6; $s++):
  $lab = 'S'.$s;
  $d = in_array($lab, $taken);
?>
<label class="seat <?= $d ? 'taken' : 'free' ?>">
<input type="checkbox" name="seat[]" value="<?= $lab ?>" hidden <?= $d ? 'disabled' : '' ?>>
<?= $lab ?>
</label>
<?php endfor; ?>
</div>

<?php else: ?>
<h3>Economy Class</h3>

<div class="seat-grid">
<?php
$max = ($type == 'B737') ? 36 : 30;
for ($s = 7; $s <= $max; $s++):
  $lab = 'S'.$s;
  $d = in_array($lab, $taken);
?>
<label class="seat <?= $d ? 'taken' : 'free' ?>">
<input type="checkbox" name="seat[]" value="<?= $lab ?>" hidden <?= $d ? 'disabled' : '' ?>>
<?= $lab ?>
</label>
<?php endfor; ?>
</div>
<?php endif; ?>

<br>

<a class="btn secondary" href="step_details.php">← Back</a>
<button class="btn">Proceed to Payment</button>

</form>
</div>

<script>
const boxes=[...document.querySelectorAll('input[name="seat[]"]')];

boxes.forEach(b=>b.addEventListener('change',()=>{
 if(document.querySelectorAll('input[name="seat[]"]:checked').length > <?= $c ?>){
   b.checked=false;
   alert("Select only <?= $c ?> seats");
 }
}));

document.querySelectorAll('.seat').forEach(el=>{
 el.addEventListener('click',()=>{
   if(el.classList.contains('taken')) return;
   const cb=el.querySelector('input');
   if(cb && !cb.disabled){
     cb.checked=!cb.checked;
     el.classList.toggle('selected',cb.checked);
   }
 });
});
</script>

<?php include 'partials/footer.php'; ?>
