<?php
include '../con.php';
$conn = new Con();
$db = $conn->connect();
session_start();

$phone = $_POST['phone'] ?? '';
$otpError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredOtp = $_POST['otp'] ?? '';
   // $phone = $_SESSION['phone'] ?? ''; // Ensure the phone number is available in session

    if (empty($phone)) {
        $otpError = "Session expired. Please try registering again.";
    } else {
        // Fetch the latest OTP for the user's phone number
        $query = "SELECT code FROM otp_codes WHERE phone = ? ORDER BY id DESC LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $phone);
        $stmt->execute();
        $stmt->bind_result($latestOtp);
        $stmt->fetch();
        $stmt->close();

        if ($latestOtp && $enteredOtp == $latestOtp) {
            // OTP is valid, update user status to 1
            $updateQuery = "UPDATE users SET status = 1 WHERE phone_number = ?";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bind_param('s', $phone);
            if ($updateStmt->execute()) {
                $_SESSION['message_feedback'] = "OTP Verified Successfully!";
                header("Location: login-page.php"); // Redirect after success
                exit();
            } else {
                $otpError = "Failed to update status.";
            }
            $updateStmt->close();
        } else {
            $otpError = "Invalid OTP. Please try again.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
        header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background-color: black; /* Header background color is black */
            margin-bottom: 250px;
           
        }
        header h1 a {
            color: green !important; /* Header text color is green */
        }
        .main2 {
            margin-top: 150px; /* Reduced margin to align content below the header */
            margin-bottom: 100px; /* Add margin to ensure content is not hidden behind the footer */
            width: 100%; /* Make the main container full width */
            color: black; /* Keep content text color black */
            padding-top: 120px; /* Reduced padding to bring content closer to the top */
        }
        
    </style>

</head>
<body>
    <header>
        <h1><a href="../index.php" class="text-white text-decoration-none">&larr;</a> OTP Verification</h1>
    </header>

    <main2 class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4">
                    <h3 class="text-center">VERIFY & ACTIVATE YOUR ACCOUNT HERE</h3>
                    <?php if ($otpError): ?>
                        <div class="alert alert-danger"><?php echo $otpError; ?></div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="otp" class="form-label">Phone Number</label>
                            <input type="number" name="phone" id="phone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="otp" class="form-label">OTP Code</label>
                            <input type="number" name="otp" id="otp" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Verify OTP</button>
                    </form>
                </div>
            </div>
        </div>
    </main2>

    <footer class="bg-dark text-white text-center py-3">
        <img src="../fnb foundation.png" alt="Image 3" class="mx-2" style="width: 64px; height: 45px;">
        <img src="../icon.png" alt="Image 2" class="mx-2" style="width: 45px; height: 45px;">    
        <img src="../ki.png" alt="Image 1" class="mx-2" style="width: 45px; height: 45px;">
    </footer>
</body>
</html>