<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


ini_set('log_errors', 1);
ini_set('error_log', 'login-error.log');


include('con.php');
$conn = new Con();
$db = $conn->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user details from the database
    $sql = "SELECT user_id, name, password_hash FROM users WHERE email = ? and user_role = 'admin' and status = 1";
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($user_id, $name,  $hashed_password);
        $stmt->fetch();

        // Check if the user exists
        if ($user_id && password_verify($password, $hashed_password)) {
            // Store user ID in session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['loggedin'] = true;
            $_SESSION['name'] = $name;

            // Redirect to the main page or dashboard
            header("Location: index.php");
            exit;
        } else {
            // Invalid credentials
            $error = "Invalid username or password.";
        }
        $stmt->close();
    } else {
        $error = "Failed to prepare statement.";
    }

    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="login-styles.css">
</head>
<body>

  <div class="container">
    <div class="login-box">
      <h2>Login</h2>
      <?php if (isset($error)): ?>
          <p class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endif; ?>
      <form action="" method="POST">
        <div class="user-box">
          <input type="text" name="username" required>
          <label>Email</label>
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
    </div>
  </div>

</body>
</html>
