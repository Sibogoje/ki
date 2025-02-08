<?php
include('../zon.php');
$conn = new Con();
$db = $conn->connect();

$id = $_GET['id'];

$sql = "DELETE FROM reminders WHERE reminder_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    header('Location: ../reminders.php');
} else {
    echo "Error: " . $db->error;
}

$stmt->close();
$db->close();
?>
