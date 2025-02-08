<?php

// Enable error logging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to database
include '../../con.php';
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
$counselorName = $postData['userID'];
$mode = $postData['mode'];
$user = $postData['userID'];

try {
    // Fetch counselor ID


    // Insert into appointments
    $stmt = $conn->prepare("INSERT INTO bookings (booking_date, counselor_id, mode, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sisi", $datetime, $counselorName, $mode, $user);
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