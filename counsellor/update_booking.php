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
        // If status is confirmed, send SMS to client
        if ($status === 'confirmed') {
            // Fetch client and counselor details
            $query = "SELECT u.phone_number, u.name AS client_name, u.surname AS client_surname, c.name AS counselor_name, c.surname AS counselor_surname 
                      FROM bookings b 
                      JOIN users u ON b.user_id = u.user_id 
                      JOIN users c ON b.counselor_id = c.user_id 
                      WHERE b.booking_id = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param('i', $bookingId);
            $stmt->execute();
            $result = $stmt->get_result();
            $details = $result->fetch_assoc();
            $stmt->close();

            if ($details) {
                $phone_number = $details['phone_number'];
                $client_name = $details['client_name'] . ' ' . $details['client_surname'];
                $counselor_name = $details['counselor_name'] . ' ' . $details['counselor_surname'];

                // Format booking date to remove seconds
                $formattedBookingDate = date('Y-m-d H:i', strtotime($bookingDate));

                // Send SMS via API
                $apiKey = 'c2lib25pc29sc2liYW5kemVAZ21haWwuY29tLXJlYWxzbXM=';
                $message = "Dear $client_name, Your appointment with counselor $counselor_name has been confirmed for $formattedBookingDate. Mode: $mode. See you soon, keep safe!";
                $url = "https://www.realsms.co.sz/urlSend?_apiKey=$apiKey&dest=$phone_number&message=" . urlencode($message);

                // Use cURL to send the SMS
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);

                if ($response === false) {
                    error_log('Failed to send SMS to client.');
                }
            }
        }

        echo json_encode(['success' => true]);
    } else {
        // Capture and output the error message for debugging
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $db->close();
}
?>
