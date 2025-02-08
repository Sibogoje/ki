<?php
include('../con.php');
$conn = new Con();
$db = $conn->connect();

$appointmentId = $_GET['id'];

$sql = "SELECT a.appointment_id, a.user_id, a.counselor_id, a.appointment_date, a.status 
        FROM appointments a
        WHERE a.appointment_id = ?";

$stmt = $db->prepare($sql);
$stmt->bind_param('i', $appointmentId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $appointment = $result->fetch_assoc();
    echo json_encode($appointment);
} else {
    echo json_encode(['error' => 'Appointment not found']);
}

$stmt->close();
$db->close();
?>
