<?php
require_once 'config.php';
if (!isset($_SESSION['user'], $_POST['count'])) {
    header("Location: index.php");
    exit;
}
$c = (int)$_POST['count'];
$fid = (int)$_POST['flight_id'];
$class = $_POST['class'];
include 'partials/header.php';
?>
<h2>Step 2 — Passenger Details</h2>

<form method="POST" action="step_seats.php" class="card">
<input type="hidden" name="flight_id" value="<?=$fid?>">
<input type="hidden" name="class" value="<?=$class?>">
<input type="hidden" name="count" value="<?=$c?>">

<?php for ($i=1; $i<=$c; $i++): ?>
<div class="card">
<b>Passenger <?=$i?></b><br>
Name <input name="name[]" required>
Age <input type="number" name="age[]" required>
Gender <select name="gender[]"><option>Male</option><option>Female</option></select><br><br>
Phone <input name="phone[]" required>
</div>
<?php endfor; ?>

<button>Continue</button>
</form>
<?php include 'partials/footer.php'; ?>
