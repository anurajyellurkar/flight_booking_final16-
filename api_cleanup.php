<?php
require_once 'config.php';

$conn->query("
UPDATE seats
SET status='FREE', held_until=NULL
WHERE status='HELD' AND held_until < NOW()
");
