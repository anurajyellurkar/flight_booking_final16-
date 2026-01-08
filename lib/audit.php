<?php
function log_action($conn, $user_id, $role, $action, $details) {
    $stmt = $conn->prepare("
        INSERT INTO audit_log (user_id, role, action, details)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("isss", $user_id, $role, $action, $details);
    $stmt->execute();
}
