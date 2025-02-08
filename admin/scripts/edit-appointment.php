<?php
include('../con.php');
$conn = new Con();
$db = $conn->connect();

$appointmentId = $_POST['editAppointmentId'];
$appointmentDate = $_POST['editAppointmentDate'];
$status = $_POST['editStatusSelect'];

$sql = "UPDATE bookings 
        SET status = ?, booking_date = ?
        WHERE booking_id = ?";

$stmt = $db->prepare($sql);
$stmt->bind_param('ssi', $status, $appointmentDate, $appointmentId);

if ($stmt->execute()) 
{ echo "<script>alert('Appointment updated successfully'); window.location.href='../all-appointments.php';</script>"; } 
else 
{ echo "<script>alert('Failed to update appointment'); window.location.href='../all-appointments.php';</script>";
}
$stmt->close();
$db->close();
?>
