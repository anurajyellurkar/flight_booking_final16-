<?php
require_once 'config.php';
require_once 'lib/fpdf.php';

$ticket = $_GET['t'] ?? '';
if (!$ticket) die('Ticket required');

// fetch booking
$stmt = $conn->prepare("
    SELECT b.*, f.flight_no, f.origin, f.destination, f.depart_time
    FROM bookings b
    JOIN flights f ON f.id = b.flight_id
    WHERE b.ticket_no = ?
");
$stmt->bind_param("s", $ticket);
$stmt->execute();
$b = $stmt->get_result()->fetch_assoc();

if (!$b) die('Invalid ticket');

// authorization
if (
    !isset($_SESSION['user']) ||
    ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['id'] != $b['user_id'])
) {
    die('Unauthorized');
}

// passengers
$ps = $conn->prepare("SELECT name, seat_no FROM passengers WHERE booking_id=?");
$ps->bind_param("i", $b['id']);
$ps->execute();
$ps = $ps->get_result();

// generate PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

$pdf->Cell(0, 10, 'AIRLINE BOARDING PASS', 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 8, 'Ticket: '.$b['ticket_no'], 0, 1);
$pdf->Cell(0, 8, 'Flight: '.$b['flight_no'], 0, 1);
$pdf->Cell(0, 8, 'Route: '.$b['origin'].' -> '.$b['destination'], 0, 1);
$pdf->Cell(0, 8, 'Departure: '.$b['depart_time'], 0, 1);

$pdf->Ln(5);
$pdf->Cell(0, 8, 'Passengers:', 0, 1);

while ($p = $ps->fetch_assoc()) {
    $pdf->Cell(0, 8, '- '.$p['name'].' | Seat '.$p['seat_no'], 0, 1);
}

$pdf->Ln(5);
$pdf->Cell(0, 8, 'Class: '.$b['class_type'], 0, 1);
$pdf->Cell(0, 8, 'Total Paid: Rs '.$b['total_price'], 0, 1);

$pdf->Output('I', 'ticket.pdf');
