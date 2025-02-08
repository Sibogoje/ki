<?php
include('../con.php');
$conn = new Con();
$db = $conn->connect();

// Check if 'id' parameter is provided
if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // Prepare the SQL statement to delete the appointment
    $stmt = $db->prepare("DELETE FROM bookings WHERE booking_id = ?");
    $stmt->bind_param("i", $appointment_id);

    // Execute the query and check if the appointment was deleted successfully
    if ($stmt->execute()) {
        // Redirect to the appointments page with a success message
        header('Location: ../all-appointments.php?message=Appointment deleted successfully');
    } else {
        // Redirect to the appointments page with an error message
        header('Location: ../all-appointments.php?error=Failed to delete appointment');
    }

    // Close the prepared statement and database connection
    $stmt->close();
} else {
    // Redirect to the appointments page if no ID is provided
    header('Location: all-appointments.php?error=No appointment ID provided');
}

$db->close();
?>
