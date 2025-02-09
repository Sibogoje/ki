<?php
// Include necessary files
include 'admin/zon.php';
session_start();
// Database connection
$conn = new Con();
$db = $conn->connect();


// Enable error reporting
ini_set('display_errors', 1); // Show errors on the screen
ini_set('display_startup_errors', 1); // Show startup errors
error_reporting(E_ALL); // Report all errors (not just fatal errors)

// Your PHP code goes here...



ini_set('log_errors', 1); // Enable error logging
ini_set('error_log', 'errors.log'); // Set the log file location


// Fetch available counselors from the users table
$sql = "SELECT user_id, name, surname FROM users WHERE user_role = 'client'";
$result = $db->query($sql);

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $counselor_id = $_POST['counselor_id'];
    $booking_date = $_POST['booking_date'];
    $mode = $_POST['mode'];

    // Prepare query to insert the booking into the bookings table
    $sql = "INSERT INTO bookings (user_id, counselor_id, booking_date, mode) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('iiss', $_SESSION['counselor_id'], $counselor_id, $booking_date, $mode);
    if ($stmt->execute()) {
        $success_message = "Booking successful!";
        
        // Reset the form using JavaScript (this will execute when the page reloads)
        echo '<script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function() {
                    document.getElementById("bookingForm").reset();
                });
              </script>';
    } else {
        $error_message = "Failed to make booking. Please try again.";
    }
    $stmt->close();
}

// Fetch existing bookings for the logged-in user
$sql = "SELECT b.booking_id, u.name AS client_name, u.surname AS client_surname, u.phone_number, b.booking_date, b.mode, b.status FROM bookings b
        JOIN users u ON b.user_id = u.user_id
        WHERE b.counselor_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $_SESSION['counselor_id']);
$stmt->execute();
$bookings = $stmt->get_result();
$stmt->close();

$db->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Bookings</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">
    
 <!-- Include Flatpickr CSS -->
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
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <?php 
        include('header.php');
        ?>

        <!-- Content Start -->
        <div class="content">
            <?php include('navbar.php'); ?>




            <!-- Bookings Table Start -->
            <div class="container-fluid pt-4 px-4">
                    <div class="col-12">
                        <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4">My Bookings</h6>
                            <div class="table-responsive">
                            <table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Client</th>
            <th>Phone Number</th>
            <th>Booking Date & Time</th>
            <th>Status</th>
            <th>Mode</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($bookings->num_rows > 0) {
            while ($row = $bookings->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['booking_id']; ?></td>
                    <td><?php echo $row['client_name']." ".$row['client_surname']; ?></td>
                    <td><?php echo $row['phone_number']; ?></td>
                    <td><?php echo $row['booking_date']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo ucfirst($row['mode']); ?></td>
                    <td>
                        <!-- Edit Link with Data Attributes -->
                        <a href="#" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editBookingModal"
                           data-booking-id="<?php echo $row['booking_id']; ?>"
                           data-status="<?php echo $row['status']; ?>"
                           data-booking-date="<?php echo $row['booking_date']; ?>"
                           data-mode="<?php echo $row['mode']; ?>">Edit</a>

                        <!-- Delete Link -->
                        <a href="delete_booking.php?booking_id=<?php echo $row['booking_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</a>
                    </td>
                </tr>
            <?php } 
        } else { ?>
            <tr>
                <td colspan="7" class="text-center">You have no bookings yet.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

                            </div>
                        </div>
                    </div>
                    </div>
            <!-- Bookings Table End -->

            <?php include('footer.php'); ?>
        </div>
        <!-- Content End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>
    
<!-- Modal for Editing Booking -->
<div class="modal fade" id="editBookingModal" tabindex="-1" aria-labelledby="editBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBookingModalLabel">Edit Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editBookingForm">
                    <input type="hidden" name="booking_id" id="booking_id">

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" name="status" id="status">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="canceled">Canceled</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="booking_date" class="form-label">Booking Date & Time</label>
                        <input type="datetime-local" class="form-control" name="booking_date" id="booking_date">
                    </div>

                    <div class="mb-3">
                        <label for="mode" class="form-label">Mode</label>
                        <select class="form-control" name="mode" id="mode" >
                            <option value="Online">Virtual</option>
                            <option value="Offline">In Person</option>

                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to populate modal fields with selected booking data
$('#editBookingModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var bookingId = button.data('booking-id');
    var status = button.data('status');
    var bookingDate = button.data('booking-date');
    var mode = button.data('mode');

    // Populate modal fields
    var modal = $(this);
    modal.find('#booking_id').val(bookingId);
    modal.find('#status').val(status);
    modal.find('#booking_date').val(bookingDate);
    modal.find('#mode').val(mode);
});

</script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#booking_date", {
            enableTime: true,   // Enable time selection
            dateFormat: "Y-m-d H:i",   // Date and time format
            minuteIncrement: 15,    // Time stepping (15 minutes)
            disableMobile: true    // Disable mobile datepicker (to use desktop version)
        });
    });
</script>
<script type="text/javascript">
        // Use setTimeout to hide the success message after 5 seconds
        setTimeout(function() {
            var successMessage = document.getElementById("successMessage");
            if (successMessage) {
                successMessage.style.display = "none";
            }
        }, 5000); // 5000 milliseconds = 5 seconds
    </script>

<script>
    $('#editBookingForm').submit(function (event) {
    event.preventDefault(); // Prevent default form submission

    // Get form data
    var formData = $(this).serialize();

    // AJAX request to update the booking
    $.ajax({
        url: 'update_booking.php', // Script to update the booking
        method: 'POST',
        data: formData,
success: function(response) {
    if (response.success) {
        // Close the modal
        $('#editBookingModal').modal('hide');

        // Optionally reload the page or update the table dynamically
        alert('Booking updated successfully!');
        location.reload(); // Reload the page to reflect changes
    } else {
        // If the response contains an error, show it
        var errorMessage = response.error || 'Update Done'; // Use the error message from PHP if available
       // alert(errorMessage); // Display the error message
        location.reload(); // Reload the page to reflect changes
    }
},
error: function(xhr, status, error) {
    // Display the detailed error from AJAX
    alert('An error occurred: ' + xhr.responseText + '\nStatus: ' + status + '\nError: ' + error);
}

    });
});

</script>

    <!-- JavaScript Libraries -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>


    



</body>

</html>
