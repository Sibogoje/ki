<?php
// Database connection
include('../zon.php');
$conn = new Con();
$db = $conn->connect();

// Fetch events from the database
$sql = "SELECT appointment_id, appointment_date, status, mode FROM appointments";
$result = $db->query($sql);

$events = array();
while ($row = $result->fetch_assoc()) {
    $events[] = array(
        'id' => $row['appointment_id'],
        'title' => $row['appointment_date'],
        'start' => $row['status'],
        'end' => $row['mode']
    );
}

echo json_encode($events);
?>
