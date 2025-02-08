<?php
include('../con.php');
$conn = new Con();
$db = $conn->connect();

$data = json_decode(file_get_contents('php://input'), true);

$client_id = filter_var($data['client_id'], FILTER_VALIDATE_INT);
$counselor_id = filter_var($data['counselor_id'], FILTER_VALIDATE_INT);
$appointment_date = filter_var($data['appointment_date'], FILTER_SANITIZE_STRING);
$mode = filter_var($data['mode'], FILTER_SANITIZE_STRING);


if (!$client_id || !$counselor_id || !$appointment_date || !$mode) {
    echo "Invalid input.";
    exit;
}

$sql = "INSERT INTO bookings (user_id, counselor_id, booking_date, status, mode) 
        VALUES (?, ?, ?, 'pending', ?)";

$stmt = $db->prepare($sql);
$stmt->bind_param("iiss", $client_id, $counselor_id, $appointment_date, $mode);

if ($stmt->execute()) {
    echo "Appointment booked successfully.";
} else {
    echo "There was an error booking.";
}

$stmt->close();
$db->close();
?>

