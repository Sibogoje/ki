<?php

// Connect to database
include '../../zon.php';
$con = new Con();
$conn = $con->connect();

// Query trending content
$stmt = $conn->prepare("SELECT * FROM trending ORDER BY created_at DESC");
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