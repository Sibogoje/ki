<?php

// Connect to database
include '../../con.php';
$con = new Con();
$conn = $con->connect();

// Query news articles
$stmt = $conn->prepare("SELECT title, content AS content FROM news ORDER BY created_at DESC");
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