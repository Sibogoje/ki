<?php
include('../zon.php');
$conn = new Con();
$db = $conn->connect();

// Fetch events from the database
$sql = "SELECT appointment_id AS id, 
               appointment_date AS start, 
               CONCAT('Appointment with ', (SELECT full_name FROM users WHERE user_id = appointments.user_id)) AS title 
        FROM appointments
        WHERE appointment_date >= CURDATE()";

$result = $db->query($sql);

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

header('Content-Type: application/json');
echo json_encode($events);

$db->close();
?>
