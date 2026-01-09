<?php
require_once 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $conn->prepare(
        "INSERT INTO users (name, email, password) VALUES (?, ?, MD5(?))"
    );

    try {
        $stmt->bind_param(
            "sss",
            $_POST['name'],
            $_POST['email'],
            $_POST['password']
        );
        $stmt->execute();

        // success
        header("Location: login.php?registered=1");
        exit;

    } catch (mysqli_sql_exception $e) {

        // duplicate email
        if ($e->getCode() == 1062) {
            $message = "Email already registered. Please login.";
        } else {
            $message = "Registration failed. Please try again.";
        }
    }
}
?>

<?php include 'partials/header.php'; ?>

<h2>Register shiv </h2>


<?php if ($message): ?>
  <div class="card" style="color:#ff6b6b">
    <?= htmlspecialchars($message) ?>
  </div>
<?php endif; ?>

<form method="POST" class="card">
  Name<br>
  <input name="name" required><br><br>

  Email<br>
  <input type="email" name="email" required><br><br>

  Password<br>
  <input type="password" name="password" required><br><br>

  <button>Create Account</button>
</form>

<?php include 'partials/footer.php'; ?>
