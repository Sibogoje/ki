<?php
session_start();
include('../con.php'); // Include your database configuration file

$conn = new Con();
$db = $conn->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone_number = $_POST['phone_number'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate input
    if (empty($phone_number) || empty($password)) {
        $_SESSION['error'] = 'Phone number and password are required.';
        header('Location: login-page.php');
        exit();
    }

    // Query to check if the user exists
    $query = "SELECT user_id, phone_number, password_hash, status FROM users WHERE phone_number = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $phone_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password_hash'])) {
            // Check account status
            if ($user['status'] == 1) {
                // Set session variables and log in the user
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['phone_number'] = $user['phone_number'];

                // Redirect to the home page
                header('Location: ../index.php');
                exit();
            } elseif ($user['status'] == 0) {
                $_SESSION['error'] = 'Account not activated. Contact Kwakha Indvodza.';
            } elseif ($user['status'] == 2) {
                $_SESSION['error'] = 'Account blocked. Contact Kwakha Indvodza.';
            }
        } else {
            $_SESSION['error'] = 'Invalid phone number or password.';
        }
    } else {
        $_SESSION['error'] = 'Invalid phone number or password.';
    }

    $stmt->close();
    $db->close();
}

// Redirect back to the login page with an error message
header('Location: login-page.php');
exit();
?>
