<?php

// Connect to database
include '../../zon.php';
$con = new Con();
$conn = $con->connect();

// Query articles
$stmt = $conn->prepare("SELECT title, summary AS content FROM articles ORDER BY created_at DESC");
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