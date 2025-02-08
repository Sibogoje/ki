<?php
session_start();
include('../zon.php'); // Include your database configuration file

// Function to log messages to a file
function log_message($message) {
    $log_file = '../logs/login_debug.log';
    $current_time = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$current_time] $message\n", FILE_APPEND);
}

log_message('Login process started.');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new Con();
    $db = $conn->connect();
    $username = $_POST['username'];
    $password = $_POST['password'];

    log_message("Received POST request with username: $username");

    // Validate input
    if (empty($username) || empty($password)) {
        log_message('Username or password is empty.');
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
        log_message("User found with username: $username");

        // Verify password
        if (password_verify($password, $user['password_hash'])) {
            log_message('Password verified.');

            // Check account status
            if ($user['status'] == 1) {
                log_message('Account is active.');
                // Set session variables and log in the user
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];

                // Redirect to the home page
                header('Location: ../index.php');
                exit();
            } elseif ($user['status'] == 0) {
                log_message('Account not activated.');
                $_SESSION['error'] = 'Account not activated. Contact Kwakha Indvodza.';
            } elseif ($user['status'] == 2) {
                log_message('Account blocked.');
                $_SESSION['error'] = 'Account blocked. Contact Kwakha Indvodza.';
            }
        } else {
            log_message('Invalid password.');
            $_SESSION['error'] = 'Invalid username or password.';
        }
    } else {
        log_message('Invalid username.');
        $_SESSION['error'] = 'Invalid username or password.';
    }

    $stmt->close();
    $db->close();
} else {
    log_message('Invalid request method.');
}

// Redirect back to the login page with an error message
header('Location: login-page.php');
exit();
?>
