<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Flight Booking</title>

<style>
:root{
  --bg:#0b1220;
  --card:#121c33;
  --primary:#3b82f6;
  --text:#e5edff;
  --muted:#9aa8d1;
}

/* ===== Seat Selection UI ===== */
.seat-area{
  display:flex;
  flex-direction:column;
  gap:16px;
}

.seat-grid{
  display:grid;
  grid-template-columns:repeat(6, 1fr);
  gap:10px;
  max-width:420px;
}

.seat{
  padding:10px 0;
  text-align:center;
  border-radius:10px;
  font-size:13px;
  cursor:pointer;
  user-select:none;
  background:#0e1730;
  border:1px solid #1d2a52;
}

.seat.free{background:#0e1730}
.seat.selected{background:#3b82f6;color:white}
.seat.taken{
  background:#1f2937;
  color:#6b7280;
  cursor:not-allowed;
}

.seat-legend{
  display:flex;
  gap:16px;
  font-size:13px;
  color:#9aa8d1;
}

.seat-legend span{
  display:flex;
  align-items:center;
  gap:6px;
}

.legend-box{
  width:14px;
  height:14px;
  border-radius:4px;
}
.legend-free{background:#0e1730;border:1px solid #1d2a52}
.legend-selected{background:#3b82f6}
.legend-taken{background:#1f2937}


*{box-sizing:border-box}

body{
  margin:0;
  font-family:Inter,system-ui,Arial;
  background:var(--bg);
  color:var(--text);
}

a{color:var(--primary);text-decoration:none}
a:hover{opacity:.85}

.container{
  max-width:1100px;
  margin:auto;
  padding:20px;
}

header{
  background:#0e1730;
  border-bottom:1px solid #1d2a52;
}

.nav{
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:14px 20px;
}

.brand{
  font-size:20px;
  font-weight:600;
  letter-spacing:.5px;
}

.nav a{
  margin-left:16px;
  font-size:14px;
  color:var(--muted);
}

.nav a:hover{color:white}

.card{
  background:var(--card);
  border-radius:14px;
  padding:18px;
  margin:16px 0;
}

.table{
  width:100%;
  border-collapse:collapse;
  margin-top:10px;
}

.table th,.table td{
  padding:10px;
  border-bottom:1px solid #1d2a52;
  font-size:14px;
}

.table th{
  color:var(--muted);
  font-weight:500;
}

.btn{
  background:var(--primary);
  color:white;
  border:none;
  padding:9px 14px;
  border-radius:10px;
  font-size:14px;
  cursor:pointer;
}

.btn.secondary{
  background:#1d2a52;
}

input,select{
  width:100%;
  padding:10px;
  margin-top:6px;
  border-radius:10px;
  border:1px solid #1d2a52;
  background:#0e1730;
  color:white;
}

input:focus,select:focus{
  outline:none;
  border-color:var(--primary);
}

/* wizard */
.wizard{
  display:flex;
  gap:8px;
  margin-bottom:18px;
}

.wizard div{
  flex:1;
  text-align:center;
  padding:8px;
  font-size:13px;
  border-radius:10px;
  background:#0e1730;
  color:var(--muted);
}

.wizard div b{color:white}
</style>
</head>

<body>

<header>
  <div class="nav">
    <div class="brand">✈ SkyFly</div>
    <div>
      <a href="index.php">Search</a>
      <?php if(isset($_SESSION['user'])): ?>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
      <?php endif; ?>
    </div>
  </div>
</header>

<div class="container">
