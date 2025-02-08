<?php
include('../zon.php');
$conn = new Con();
$db = $conn->connect();

$appointmentId = $_POST['appointment_id'];
$userId = $_POST['user_id'];
$appointmentDate = $_POST['appointment_date'];
$counselorId = $_POST['counselor_id'];

$sql = "UPDATE appointments 
        SET user_id = ?, appointment_date = ?, counselor_id = ?
        WHERE appointment_id = ?";

$stmt = $db->prepare($sql);
$stmt->bind_param('isii', $userId, $appointmentDate, counselorId, $appointmentId);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Appointment updated successfully']);
} else {
    echo json_encode(['error' => 'Failed to update appointment']);
}

$stmt->close();
$db->close();
?>
