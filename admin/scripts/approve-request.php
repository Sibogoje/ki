<?php
include('../con.php');
$conn = new Con();
$db = $conn->connect();

$id = $_GET['id'];

$sql = "UPDATE appointments SET status = 'confirmed' WHERE appointment_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    header('Location: ../pending-requests.php');
} else {
    echo "Error updating record: " . $db->error;
}

$stmt->close();
$db->close();
?>
