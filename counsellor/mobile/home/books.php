<?php

// Connect to database
include '../../con.php';
$con = new Con();
$conn = $con->connect();

// Query books
$stmt = $conn->prepare("SELECT title, description as content FROM books ORDER BY created_at DESC");
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