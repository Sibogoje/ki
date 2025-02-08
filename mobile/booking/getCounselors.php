<?php

// Connect to database
include '../../zon.php';
$con = new Con();
$conn = $con->connect();

// Query counselors
$stmt = $conn->prepare("SELECT name, user_id FROM users where user_role = 'counselor' ");
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