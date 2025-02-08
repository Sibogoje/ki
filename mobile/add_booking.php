<?php
// ...existing code...

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $counselor_id = $_POST['counselor_id'];
    $booking_date = $_POST['booking_date'];
    $mode = $_POST['mode'];

    // Check if the counselor is available
    $checkAvailabilityQuery = "SELECT COUNT(*) as count FROM bookings WHERE counselor_id = ? AND booking_date = ? AND status = 'confirmed'";
    $stmt = $conn->prepare($checkAvailabilityQuery);
    $stmt->bind_param("is", $counselor_id, $booking_date);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        // Counselor is not available
        echo "<div class='alert alert-danger' id='booking-feedback'>Counselor is not available at the selected time.</div>";
    } else {
        // Insert booking
        $insertBookingQuery = "INSERT INTO bookings (user_id, counselor_id, booking_date, mode) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertBookingQuery);
        $stmt->bind_param("iiss", $user_id, $counselor_id, $booking_date, $mode);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success' id='booking-feedback'>Booking successful! Refreshing...</div>";
            echo "<script>setTimeout(function() { location.reload(); }, 2000);</script>";
        } else {
            echo "<div class='alert alert-danger' id='booking-feedback'>Booking failed. Please try again.</div>";
        }
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

<script>
    // Disappear feedback after 5 seconds
    setTimeout(function() {
        var feedback = document.getElementById('booking-feedback');
        if (feedback) {
            feedback.style.display = 'none';
        }
    }, 5000);
</script>

// ...existing code...
