<?php
require_once '../config.php';

// Optional: redirect already logged-in admin
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
    header("Location: flights.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE email=? AND password=MD5(?) AND role='admin'"
    );
    $stmt->bind_param("ss", $_POST['email'], $_POST['password']);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();

    if ($admin) {
        $_SESSION['user'] = $admin;
        header("Location: flights.php");
        exit;
    }

    $error = "Invalid admin credentials";
}
?>
<!DOCTYPE html>
<html>
<body>
<h2>Admin Login</h2>
<?php if($error) echo "<p style='color:red'>$error</p>"; ?>
<form method="POST">
Email <input name="email"><br>
Password <input type="password" name="password"><br>
<button>Login</button>
</form>
</body>
</html>
