<?php
session_start();
include '../zon.php';
$conn = new Con();
$db = $conn->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['request_otp'])) {
        $phone_number = $_POST['phone_number'];

        // Generate OTP
        $otp = rand(100000, 999999);

        // Save OTP to the `otp_codes` table
        $query = "INSERT INTO otp_codes (code, phone) VALUES (?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('is', $otp, $phone_number);

        if ($stmt->execute()) {
            // Send OTP via SMS API
            $apiKey = 'c2lib25pc29sc2liYW5kemVAZ21haWwuY29tLXJlYWxzbXM=';
            $message = "Your OTP for resetting your password is: $otp";
            $url = "https://www.realsms.co.sz/urlSend?_apiKey=$apiKey&dest=$phone_number&message=" . urlencode($message);

            // Use cURL to send the SMS
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            if ($response === false) {
                $_SESSION['error'] = 'Failed to send OTP';
            } else {
                $_SESSION['success'] = 'OTP sent to your phone number.';
                $_SESSION['phone_number'] = $phone_number; // Store phone number in session
            }
        } else {
            $_SESSION['error'] = 'Failed to generate OTP';
        }

        $stmt->close();
    } elseif (isset($_POST['reset_password'])) {
        $phone_number = $_SESSION['phone_number']; // Retrieve phone number from session
        $otp = $_POST['otp'];
        $new_password = $_POST['new_password'];
        $passwordHash = password_hash($new_password, PASSWORD_BCRYPT);

        // Verify OTP
        $query = "SELECT * FROM otp_codes WHERE phone = ? AND code = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('si', $phone_number, $otp);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows;
        $stmt->close();

        if ($count > 0) {
            // Update password
            $query = "UPDATE users SET password_hash = ? WHERE phone_number = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param('ss', $passwordHash, $phone_number);

            if ($stmt->execute()) {
                $_SESSION['success'] = 'Password updated successfully!';
            } else {
                $_SESSION['error'] = 'Failed to update password';
            }

            $stmt->close();
        } else {
            $_SESSION['error'] = 'Invalid OTP';
        }
    }

    $db->close();
    header('Location: forgot_password.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        header, footer {
            width: 100%;
            text-align: center;
            padding: 1px;
            background-color: #004080;
            color: green;
        }
        .container {
            background-color: #004080;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            margin: 10px 0;
            color: white;
        }
        .container h1 {
            margin-bottom: 20px;
            color: #333333;
        }
        .container input[type="text"], .container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #cccccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .container button {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            box-sizing: border-box;
        }
        .container button:hover {
            background-color: #0056b3;
        }
        .container .message {
            margin-top: 20px;
        }
        .container .message p {
            color: #ff0000;
        }
        .container .message p.success {
            color: #00a65a;
        }
    </style>
</head>
<body>
    <header>
        <h2><a href="../index.php" class="text-white text-decoration-none">&#x21A9;</a> A SAFE SPACE FOR YOU</h2>
    </header>
    <div class="container">
        <h1>Forgot Password</h1>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="message"><p>' . $_SESSION['error'] . '</p></div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="message"><p class="success">' . $_SESSION['success'] . '</p></div>';
            unset($_SESSION['success']);
        }
        ?>
        <form method="POST" action="forgot_password.php">
            <input type="text" id="phone_number" name="phone_number" placeholder="Phone Number" required>
            <button type="submit" name="request_otp">Request OTP</button>
        </form>
        <form method="POST" action="forgot_password.php">
            <input type="text" id="otp" name="otp" placeholder="OTP" required>
            <input type="password" id="new_password" name="new_password" placeholder="New Password" required>
            <button type="submit" name="reset_password">Reset Password</button>
        </form>
    </div>
    <footer class="bg-dark text-white text-center py-3">
    <img src="../fnb foundation.png" alt="Image 3" class="mx-2" style="width: 64px; height: 45px;">
    <img src="../icon.png" alt="Image 2" class="mx-2" style="width: 45px; height: 45px;">    
    <img src="../ki.png" alt="Image 1" class="mx-2" style="width: 45px; height: 45px;">
    </footer>
</body>
</html>
