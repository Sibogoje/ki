<?php
// Database connection
include('../zon.php');
$conn = new Con();
$db = $conn->connect();

$booking_id = $_GET['booking_id'] ?? '';

// Log the booking ID being used
error_log("Fetching details for booking ID: " . $booking_id);

// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $db->connect_error);
    die("Connection failed: " . $db->connect_error);
}

// Query to fetch booking details
$sql = "SELECT 
            booking_date, 
            `STATUS`, 
            MODE, 
            counselor_surname, 
            counselor_name, 
            cancellation_reason, 
            last_modified_at, 
            approved_by_counselor 
        FROM booking_info 
        WHERE booking_id = ?";

// Prepare and execute the query
$stmt = $db->prepare($sql);
if (!$stmt) {
    error_log("Prepare failed: " . $db->error);
    http_response_code(500);
    echo json_encode(["error" => "Failed to prepare query"]);
    exit;
}
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    error_log("Query failed: " . $db->error);
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch booking details"]);
    exit;
}

$booking = $result->fetch_assoc();

// Ensure no HTML is sent in the response
header('Content-Type: application/json');
echo json_encode($booking);

$stmt->close();
$db->close();
?>
