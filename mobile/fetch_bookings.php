<?php
// Database connection
include('../zon.php');
$conn = new Con();
$db = $conn->connect();

$phone = $_GET['phone'] ?? '';

// Log the phone number being used
error_log("Fetching bookings for phone number: " . $phone);

// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $db->connect_error);
    die("Connection failed: " . $db->connect_error);
}

// Query to fetch bookings
$sql = "SELECT 
            booking_id,
            booking_date, 
            `STATUS`, 
            MODE, 
            counselor_surname, 
            counselor_name, 
            cancellation_reason, 
            last_modified_at, 
            approved_by_counselor 
        FROM booking_info 
        WHERE user_phone_number = ?";

// Prepare and execute the query
$stmt = $db->prepare($sql);
if (!$stmt) {
    error_log("Prepare failed: " . $db->error);
    http_response_code(500);
    echo json_encode(["error" => "Failed to prepare query"]);
    exit;
}
$stmt->bind_param("s", $phone);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    error_log("Query failed: " . $db->error);
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch bookings"]);
    exit;
}

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

// Ensure no HTML is sent in the response
header('Content-Type: application/json');
echo json_encode($bookings);

$stmt->close();
$db->close();
?>
