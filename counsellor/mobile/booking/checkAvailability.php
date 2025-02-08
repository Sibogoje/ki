<?php

// Connect to database
include '../../con.php';
$con = new Con();
$conn = $con->connect();

// Get JSON input
$jsonInput = file_get_contents('php://input');

// Decode JSON
$postData = json_decode($jsonInput, true);

// Log received data
file_put_contents('check_appointment_log.txt', "POST Data: " . json_encode($postData) . "\n", FILE_APPEND);

// Extract JSON data
$datetime = $postData['datetime'];
$counselor = $postData['counselor'];

// Calculate end time (1 hour after requested time)
$end_time = date('Y-m-d H:i:s', strtotime($datetime) + 3600);

// Fetch counselor ID
$stmt = $conn->prepare("SELECT id FROM counselors WHERE full_name = ?");
$stmt->bind_param("s", $counselor);
$stmt->execute();
$result = $stmt->get_result();
$counselorRow = $result->fetch_assoc();
$counselorId = $counselorRow['id'];

file_put_contents('check_appointment_log.txt', "Counselor ID: " . $counselorId . "\n", FILE_APPEND);

// Check counselor availability
$stmt = $conn->prepare("SELECT COUNT(*) FROM appointments
                        WHERE counselor_id = ? AND (
                          (appointment_date BETWEEN ? AND ?)
                          OR (appointment_date <= ? AND DATE_ADD(appointment_date, INTERVAL 1 HOUR) > ?)
                        )");

$stmt->bind_param("sssss", $counselorId, $datetime, $end_time, $datetime, $datetime);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Return JSON response with availability
$available = ($row['COUNT(*)'] == 0);
$response = array('available' => $available);
header('Content-Type: application/json');
echo json_encode($response);

?>