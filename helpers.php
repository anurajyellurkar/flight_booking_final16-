<?php
function takenSeats($conn, $flight_id) {
    $stmt = $conn->prepare("
        SELECT p.seat_no
        FROM passengers p
        JOIN bookings b ON b.id = p.booking_id
        WHERE b.flight_id = ?
          AND p.cancel_status = 'ACTIVE'
    ");
    $stmt->bind_param("i", $flight_id);
    $stmt->execute();
    $res = $stmt->get_result();

    $seats = [];
    while ($row = $res->fetch_assoc()) {
        $seats[] = $row['seat_no'];
    }
    return $seats;
}
