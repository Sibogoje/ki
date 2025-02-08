<?php

// Enable error logging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to database
include '../../zon.php';
$con = new Con();
$conn = $con->connect();

// Get JSON input
$jsonInput = file_get_contents('php://input');

// Decode JSON
$postData = json_decode($jsonInput, true);

// Log received data
file_put_contents('insert_appointment_log.txt', "POST Data: " . json_encode($postData) . "\n", FILE_APPEND);

// Extract data
$datetime = $postData['datetime'];
$counselorName = $postData['counselor'];
$mode = $postData['mode'];

try {
    // Fetch counselor ID
    $stmt = $conn->prepare("SELECT id FROM counselors WHERE full_name = ?");
    $stmt->bind_param("s", $counselorName);
    $stmt->execute();
    $result = $stmt->get_result();
    $counselorRow = $result->fetch_assoc();
    $counselorId = $counselorRow['id'];

    // Insert into appointments
    $stmt = $conn->prepare("INSERT INTO appointments (appointment_date, counselor_id, mode) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $datetime, $counselorId, $mode);
    $stmt->execute();

    // Return JSON response
    $response = array('success' => true, 'message' => 'Appointment booked');
} catch (Exception $e) {
    // Log and return error
    file_put_contents('insert_appointment_error.txt', $e->getMessage() . "\n", FILE_APPEND);
    $response = array('success' => false, 'message' => 'Server error');
}

header('Content-Type: application/json');
echo json_encode($response);
exit;

?>