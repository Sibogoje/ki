<?php

// Connect to database
include '../../con.php';
$con = new Con();
$conn = $con->connect();



// Query bookings
$stmt = $conn->prepare("SELECT b.appointment_id, b.appointment_date as `date`,  b.mode, b.status, c.full_name AS counselor 
                        FROM appointments b 
                        JOIN counselors c ON b.counselor_id = c.id 
                        ORDER BY b.appointment_date DESC");

$stmt->execute();
$result = $stmt->get_result();

// Fetch all rows
$rows = array();
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}

// Return JSON response with all rows
$response = array('content' => $rows);
echo json_encode($response);

?>