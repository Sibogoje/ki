<?php
// Include the database connection file
include 'admin/zon.php';
$conn = new Con();
$db = $conn->connect();
// Start a session
session_start();

// Check if the booking_id is set in the URL
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Prepare the SQL query to delete the booking
    $sql = "DELETE FROM bookings WHERE booking_id = ?";
    $stmt = $db->prepare($sql);

    // Bind the booking_id to the statement
    $stmt->bind_param("i", $booking_id);

    // Execute the delete query
    if ($stmt->execute()) {
        // If the booking was successfully deleted, redirect with a success message
        header("Location: bookings.php?msg=Booking deleted successfully.");
        exit();
    } else {
        // If there was an error, redirect with an error message
        header("Location: bookings.php?msg=Failed to delete the booking.");
        exit();
    }
} else {
    // If no booking_id is provided, redirect with an error message
    header("Location: bookings.php?msg=No booking ID provided.");
    exit();
}
?>
