<?php

include 'admin/zon.php';
$conn = new Con();
$db = $conn->connect();
// Start a session
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Donate</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

     <!-- Favicon -->
     <link href="../icon.png" rel="icon">

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
      include ('header.php');
      ?>


        <!-- Content Start -->
        <div class="content">
            <?php
            include('navbar.php');
            ?>
<?php
if (isset($_GET['success'])) {
    echo '<div id="alert-success" class="alert alert-success">Thank you for your donation or pledge!</div>';
}
if (isset($_GET['error'])) {
    echo '<div id="alert-error" class="alert alert-danger">Error: ' . htmlspecialchars($_GET['error']) . '</div>';
}
?>

<script>
    // JavaScript to hide alerts after 6 seconds
    setTimeout(() => {
        const successAlert = document.getElementById('alert-success');
        const errorAlert = document.getElementById('alert-error');
        if (successAlert) successAlert.style.display = 'none';
        if (errorAlert) errorAlert.style.display = 'none';
    }, 6000); // 6000 ms = 6 seconds
</script>


            <!-- Donate Form Start -->
            <div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center mx-0" style="padding: 20px;">
        <div class="col-md-8 text-center">
            <h3>Donate or Pledge</h3>
            <form action="process_donation.php" method="post">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="donorName" name="donor_name" required>
                            <label for="donorName">Full Name</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="tel" class="form-control" id="donorPhone" name="donor_phone" required>
                            <label for="donorPhone">Phone</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="email" class="form-control" id="donorEmail" name="donor_email" required>
                            <label for="donorEmail">Email</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="donationAmount" name="donation_amount" required>
                            <label for="donationAmount">Amount (SZL)</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-floating">
                            <textarea class="form-control" id="otherDonations" name="other_donations" style="height: 100px;"></textarea>
                            <label for="otherDonations">Other Donations</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12 form-check">
                        <input type="checkbox" class="form-check-input" id="isPledge" name="is_pledge">
                        <label class="form-check-label" for="isPledge">Is this a pledge?</label>
                    </div>
                </div>

                <!-- Pledge Details -->
                <div id="pledgeDetails" style="display: none;">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="pledgeDate" name="pledge_date">
                                <label for="pledgeDate">Pledge Fulfillment Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="reminderCheck" name="reminder_check">
                                <label class="form-check-label" for="reminderCheck">Would you like a reminder?</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3" id="reminderDate" style="display: none;">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="reminderDateInput" name="reminder_date">
                                <label for="reminderDateInput">Reminder Date</label>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Submit Donation</button>
            </form>
        </div>
    </div>
</div>

            <!-- Donate Form End -->

<?php
include('footer.php');
?>
        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
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

    <script>
        // Show pledge date and reminder fields if pledge is checked
        $('#isPledge').change(function() {
            if ($(this).is(':checked')) {
                $('#pledgeDetails').show();
            } else {
                $('#pledgeDetails').hide();
                $('#reminderDate').hide();
            }
        });

        // Show reminder date if reminder checkbox is checked
        $('#reminderCheck').change(function() {
            if ($(this).is(':checked')) {
                $('#reminderDate').show();
            } else {
                $('#reminderDate').hide();
            }
        });
    </script>

</body>

</html>
