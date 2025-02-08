<?php
include('../zon.php');
$conn = new Con();
$db = $conn->connect();

$reminder_id = $_POST['reminder_id'];
$appointment_id = $_POST['appointment_id'];
$reminder_date = $_POST['reminder_date'];
$message = $_POST['message'];

$sql = "UPDATE reminders SET appointment_id = ?, reminder_date = ?, message = ? WHERE reminder_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('issi', $appointment_id, $reminder_date, $message, $reminder_id);

if ($stmt->execute()) {
    header('Location: reminders.php');
} else {
    echo "Error: " . $db->error;
}

$stmt->close();
$db->close();
?>
