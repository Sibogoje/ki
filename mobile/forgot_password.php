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
            }
        } else {
            $_SESSION['error'] = 'Failed to generate OTP';
        }

        $stmt->close();
    } elseif (isset($_POST['reset_password'])) {
        $phone_number = $_POST['phone_number'];
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
</head>
<body>
    <?php
    if (isset($_SESSION['error'])) {
        echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<p style="color: green;">' . $_SESSION['success'] . '</p>';
        unset($_SESSION['success']);
    }
    ?>
    <form method="POST" action="forgot_password.php">
        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" required>
        <button type="submit" name="request_otp">Request OTP</button>
    </form>
    <form method="POST" action="forgot_password.php">
        <label for="otp">OTP:</label>
        <input type="text" id="otp" name="otp" required>
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
        <input type="hidden" name="phone_number" value="<?php echo isset($_POST['phone_number']) ? $_POST['phone_number'] : ''; ?>">
        <button type="submit" name="reset_password">Reset Password</button>
    </form>
</body>
</html>
