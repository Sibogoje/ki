<?php
// Include the database connection file
include 'admin/zon.php';
$conn = new Con();
$db = $conn->connect();
// Start a session
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute query to fetch user details (including name, surname, and role)
    $sql = "SELECT user_id, name, surname, user_role, password_hash FROM users WHERE email = ?  ";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if the user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $user_name, $user_surname, $user_role, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, start a session
            $_SESSION['counselor_id'] = $user_id;
            $_SESSION['counselor_name'] = $user_name;
            $_SESSION['counselor_surname'] = $user_surname;
            $_SESSION['counselor_role'] = $user_role;

            // Redirect to a protected page
            header("Location: dashboard.php");
            exit;
        } else {
            // Invalid password
            $error = "Invalid username or password.";
        }
    } else {
        // User does not exist
        $error = "Invalid username or password.";
    }

    $stmt->close();
}

$db->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login-styles.css?v=1.0.1">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="login-box">
        <h2>KI Counselor Login</h2>
      
        
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <form action="" method="POST">
            <div class="user-box">
                <input type="text" name="username" required>
                <label>Username</label>
            </div>
            <div class="user-box">
                <input type="password" name="password" required>
                <label>Password</label>
            </div>
            <button type="submit" class="btn">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                Login
            </button>
        </form>
        
        <!-- Forgot Password and Registration Links -->
        <div class="links">
            <p>
                <a href="forgot_password.php">Forgot Password?</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>
