<?php
session_start();
include '../zon.php';
$conn = new Con();
$db = $conn->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];
    $town = $_POST['town'];
    $region = $_POST['region'];
    $age = $_POST['age'];
    $marital = $_POST['marital'];
    $gender = $_POST['gender'];
    $education = $_POST['education'];
    $orphan = $_POST['orphan'];
    $disability = $_POST['disability'];
    $constituency = $_POST['constituency'];
    $community = $_POST['employment_status'];
    $user_role = "client";
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Check phone number and email uniqueness
    $query = "SELECT * FROM users WHERE phone_number = ? OR email = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ss', $phone_number, $email);
    $stmt->execute();
    $stmt->store_result();
    $count = $stmt->num_rows;
    $stmt->close();

    if ($count > 0) {
        $_SESSION['error'] = 'Account already exists';
        header('Location: register.php');
        exit();
    }

    // Insert user data into `users` table
    $query = "INSERT INTO users (name, user_role, surname, email, phone_number, password_hash, town, region, age, marital, gender, education, orphan, disability, constituency, community, created_at) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $db->prepare($query);
    $stmt->bind_param('ssssssssssssssss', $name,$user_role, $surname, $email, $phone_number, $passwordHash, $town, $region, $age, $marital, $gender, $education, $orphan, $disability, $constituency, $community);

    if ($stmt->execute()) {
        // User successfully saved, now generate and send OTP
        $otp = rand(100000, 999999);

        // Save OTP to the `otp_codes` table
        $query = "INSERT INTO otp_codes (code, phone) VALUES (?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('is', $otp, $phone_number);

        if ($stmt->execute()) {
            // Send OTP via SMS API
            $apiKey = 'c2lib25pc29sc2liYW5kemVAZ21haWwuY29tLXJlYWxzbXM=';
            $message = "Thank you for registering with Kwakha Indvodza - Bhoboka App. Your OTP is = $otp";
            $url = "https://www.realsms.co.sz/urlSend?_apiKey=$apiKey&dest=$phone_number&message=" . urlencode($message);

            // Use cURL to send the SMS
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            if ($response === false) {
                $_SESSION['error'] = 'Failed to send OTP';
                header('Location: register.php');
                exit();
            }

            // Redirect to OTP verification page
            $_SESSION['success'] = 'Account created successfully! OTP sent to your phone number.';
            header('Location: verify.php');
            exit();
        } else {
            $_SESSION['error'] = 'Failed to generate OTP';
            header('Location: register.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 'Failed to create user account';
        header('Location: register.php');
        exit();
    }

    $stmt->close();
    $db->close();
}
?>
