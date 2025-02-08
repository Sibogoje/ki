<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="reg-styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
  <div class="container">
    <div class="register-box">
      <h2>Register</h2>
      <form action="regnow.php" method="POST">
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
