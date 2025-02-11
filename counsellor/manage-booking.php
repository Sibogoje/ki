<?php
// Include necessary files
include 'admin/zon.php';
session_start();
// Database connection
$conn = new Con();
$db = $conn->connect();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch booking details
$booking_id = $_GET['booking_id'];
$sql = "SELECT * FROM bookings WHERE booking_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $booking_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $booking_date = $_POST['booking_date'];
    $mode = $_POST['mode'];
    $category = $_POST['category'];
    $comments = $_POST['comments'];

    $sql = "UPDATE bookings SET status = ?, booking_date = ?, mode = ?, category = ?, comments = ? WHERE booking_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('sssssi', $status, $booking_date, $mode, $category, $comments, $booking_id);
    if ($stmt->execute()) {
        $success_message = "Booking updated successfully!";
    } else {
        $error_message = "Failed to update booking. Please try again.";
    }
    $stmt->close();
}

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Manage Booking</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <!-- Include necessary CSS and JS files -->
    !-- Include Flatpickr CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery (necessary for Flatpickr) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <?php include('header.php'); ?>
        <div class="content">
            <?php include('navbar.php'); ?>
            <div class="container-fluid pt-4 px-4">
                <div class="col-12">
                    <div class="bg-light rounded h-100 p-4">
                        <h6 class="mb-4">Manage Booking</h6>
                        <?php if (isset($success_message)) { echo "<div class='alert alert-success'>$success_message</div>"; } ?>
                        <?php if (isset($error_message)) { echo "<div class='alert alert-danger'>$error_message</div>"; } ?>
                        <form method="POST">
                            <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="pending" <?php if ($booking['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                    <option value="confirmed" <?php if ($booking['status'] == 'confirmed') echo 'selected'; ?>>Confirmed</option>
                                    <option value="completed" <?php if ($booking['status'] == 'completed') echo 'selected'; ?>>Completed</option>
                                    <option value="canceled" <?php if ($booking['status'] == 'canceled') echo 'selected'; ?>>Canceled</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="booking_date" class="form-label">Booking Date & Time</label>
                                <input type="datetime-local" class="form-control" name="booking_date" id="booking_date" value="<?php echo date('Y-m-d\TH:i', strtotime($booking['booking_date'])); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="mode" class="form-label">Mode</label>
                                <select class="form-control" name="mode" id="mode">
                                    <option value="Online" <?php if ($booking['mode'] == 'Online') echo 'selected'; ?>>Virtual</option>
                                    <option value="Offline" <?php if ($booking['mode'] == 'Offline') echo 'selected'; ?>>In Person</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <input type="text" class="form-control" name="category" id="category" value="<?php echo $booking['category']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="comments" class="form-label">Comments</label>
                                <textarea class="form-control" name="comments" id="comments"><?php echo $booking['comments']; ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php include('footer.php'); ?>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr("#booking_date", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minuteIncrement: 15,
                disableMobile: true
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
