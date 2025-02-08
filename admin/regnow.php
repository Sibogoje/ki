<?php
session_start();

include('zon.php');
$conn = new Con();
$db = $conn->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_role = $_POST['user_role'];

    // Check if email is already registered
    $sql = "SELECT email FROM users WHERE email = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Email already registered
        $error = "This email is already registered. Please use a different email.";
    } else {
        // Hash the password securely
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into the database
        $sql = "INSERT INTO users (name, surname, email, password_hash, user_role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('sssss', $name, $surname, $email, $password_hash, $user_role);

        if ($stmt->execute()) {
            // Registration successful, redirect to login page
            header("Location: login.php");
            exit;
        } else {
            // Database error
            $error = "An error occurred while registering. Please try again.";
        }
    }

    $stmt->close();
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="reg-styles.css">
</head>
<body>

  <div class="container">
    <div class="register-box">
      <h2>Register</h2>
      <?php if (isset($error)): ?>
          <p class="error"><?php echo $error; ?></p>
      <?php endif; ?>
      <form action="" method="POST">
        <div class="user-box">
          <input type="text" name="name" required>
          <label>Name</label>
        </div>
        <div class="user-box">
          <input type="text" name="surname" required>
          <label>Surname</label>
        </div>
        <div class="user-box">
          <input type="email" name="email" required>
          <label>Email</label>
        </div>
        <div class="user-box">
          <input type="password" name="password" required>
          <label>Password</label>
        </div>
        <div class="user-box">
          <select name="user_role" required>
            <option value="" disabled selected>Select User Role</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
          </select>
          <label>User Role</label>
        </div>
        <button type="submit" class="btn">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          Register
        </button>
      </form>
    </div>
  </div>

</body>
</html>
