<?php
session_start();
include('../zon.php'); // Include your database configuration file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new Con();
    $db = $conn->connect();
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Username and password are required.';
        header('Location: login-page.php');
        exit();
    }

    // Query to check if the user exists
    $query = "SELECT user_id, username, password_hash, status FROM users WHERE username = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $username);
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
                $_SESSION['username'] = $user['username'];

                // Redirect to the home page
                header('Location: ../index.php');
                exit();
            } elseif ($user['status'] == 0) {
                $_SESSION['error'] = 'Account not activated. Contact Kwakha Indvodza.';
            } elseif ($user['status'] == 2) {
                $_SESSION['error'] = 'Account blocked. Contact Kwakha Indvodza.';
            }
        } else {
            $_SESSION['error'] = 'Invalid username or password.';
        }
    } else {
        $_SESSION['error'] = 'Invalid username or password.';
    }

    $stmt->close();
    $db->close();
}

// Redirect back to the login page with an error message
header('Location: login-page.php');
exit();
?>
