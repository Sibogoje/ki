<?php
// Include database connection
include 'admin/zon.php';
$conn = new Con();
$db = $conn->connect();
session_start();

// Enable error reporting
ini_set('display_errors', 1); // Show errors on the screen
ini_set('display_startup_errors', 1); // Show startup errors
error_reporting(E_ALL); // Report all errors (not just fatal errors)

// Your PHP code goes here...
ini_set('log_errors', 1); // Enable error logging
ini_set('error_log', 'updatebooking.log'); // Set the log file location


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $bookingId = $_POST['booking_id'];
    $status = $_POST['status'];
    $bookingDate = $_POST['booking_date'];
    $mode = $_POST['mode'];

    // Prepare SQL query to update the booking
    $sql = "UPDATE bookings SET status = ?, booking_date = ?, mode =? WHERE booking_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('sssi', $status, $bookingDate, $mode, $bookingId);

   // Check if the query executed successfully
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    // Capture and output the error message for debugging
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

}
?>
