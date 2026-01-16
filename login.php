<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $q = $conn->prepare(
        "SELECT * FROM users WHERE email=? AND password=MD5(?)"
    );
    $q->bind_param("ss", $_POST['email'], $_POST['password']);
    $q->execute();
    $u = $q->get_result()->fetch_assoc();

    if ($u) {
        $_SESSION['user'] = $u;
        header("Location: index.php");
        exit;
    }
    $error = "Invalid login";
}
include 'partials/header.php';
?>
<h2>Login here only </h2>
<?php if(!empty($error)) echo "<div class='card'>$error</div>"; ?>
<form method="POST" class="card">
<input name="email" required><br><br>
<input type="password" name="password" required><br><br>
<button>Login</button>
</form>
<?php include 'partials/footer.php'; ?>
