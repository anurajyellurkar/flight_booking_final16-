<?php
// create seats for a flight if not present
function initSeatsForFlight($conn, $flight_id, $aircraft_type = 'A320') {
    // check existing seats count
    $stmt = $conn->prepare("SELECT COUNT(*) c FROM seats WHERE flight_id = ?");
    $stmt->bind_param("i", $flight_id);
    $stmt->execute();
    $cnt = $stmt->get_result()->fetch_assoc()['c'] ?? 0;
    if ($cnt > 0) return;

    $max = ($aircraft_type === 'B737') ? 36 : 30;
    for ($i = 1; $i <= $max; $i++) {
        $seat = 'S' . $i;
        $ins = $conn->prepare("INSERT INTO seats (flight_id, seat_no, status) VALUES (?, ?, 'FREE')");
        $ins->bind_param("is", $flight_id, $seat);
        $ins->execute();
    }
}
