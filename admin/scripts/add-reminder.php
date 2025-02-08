<?php
include('../zon.php');
$conn = new Con();
$db = $conn->connect();

$appointment_id = $_POST['appointment_id'];
$reminder_date = $_POST['reminder_date'];
$message = $_POST['message'];

$sql = "INSERT INTO reminders (appointment_id, reminder_date, message) VALUES (?, ?, ?)";
$stmt = $db->prepare($sql);
$stmt->bind_param('iss', $appointment_id, $reminder_date, $message);

if ($stmt->execute()) {
    header('Location: ../reminders.php');
} else {
    echo "Error: " . $db->error;
}

$stmt->close();
$db->close();
?>
