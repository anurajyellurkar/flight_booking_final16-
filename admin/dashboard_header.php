<?php
require_once 'auth.php';
?>
<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Dashboard</title>
<style>
body{background:#0b1425;color:#e8f0ff;font-family:Arial;margin:0}
header{background:#0f1f45;padding:16px 20px;font-size:18px}
.container{max-width:1080px;margin:auto;padding:18px}
.nav{display:flex;gap:14px;margin-bottom:18px}
.nav a{padding:8px 12px;background:#2d6bff;border-radius:10px;color:white;text-decoration:none}
.card{background:#13244e;border-radius:18px;padding:16px;margin:10px 0}
</style>
</head>
<body>
<header>✈ Admin Dashboard</header>
<div class="container">
<div class="nav">
 <a href="flights.php">Flights</a>
 <a href="bookings.php">Bookings</a>
 <a href="refunds.php">Refunds</a>
 <a href="../index.php">User site</a>
</div>
