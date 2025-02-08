<?php
include('../zon.php');
$conn = new Con();
$db = $conn->connect();

// Get the requested appointment date and time from the query string
$datetime = $_GET['datetime'];

// Calculate the end time of the requested appointment (1 hour after the requested time)
$end_time = date('Y-m-d H:i:s', strtotime($datetime) + 3600);

// Query to find counselors who are available at the requested time
$sql = "SELECT user_id, name FROM users where user_role = 'counselor'
        AND user_id NOT IN (
          SELECT counselor_id FROM bookings
          WHERE (booking_date BETWEEN '$datetime' AND '$end_time')
          OR (booking_date <= '$datetime' AND DATE_ADD(booking_date, INTERVAL 1 HOUR) > '$datetime')
        )";

$result = $db->query($sql);

$counselors = [];
while ($row = $result->fetch_assoc()) {
    $counselors[] = $row;
}

header('Content-Type: application/json');
echo json_encode($counselors);

$db->close();
?>
