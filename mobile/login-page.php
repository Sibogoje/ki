<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background-color: #001f3f;
            color: #ffffff;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0; /* Remove padding */
        }
        header, footer {
            width: 100%;
            text-align: center;
            padding: 5px 5px; /* Reduce padding */
            background-color: none;
            position: fixed;
            left: 0;
        }
        header {
            top: 0;
            padding-top: 90px;
        }
        footer {
            bottom: 0;
        }
        .container {
            text-align: center;
            background-color: #004080;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            margin: 40px 0; /* Reduce margin */
        }
        .container img {
            width: 100px;
            margin-bottom: 0px;
        }
        .container h1 {
            margin-bottom: 20px;
        }
        .container input[type="text"], .container input[type="password"] {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        .container input[type="submit"] {
            background-color: rgb(51, 186, 186);
            color: rgb(0, 0, 0);
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 85%; /* Make the button width equal to the card */
            font-weight: bold;
        }
        .container input[type="submit"]:hover {
            background-color: rgb(51, 186, 186);
        }
        .sponsors {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .sponsors img {
            width: 50px;
            height: 50px;
            margin: 0 10px;
        }
        .links {
            margin-top: 20px;
        }
        .links a {
            color: #00a65a;
            text-decoration: none;
            margin: 0 10px;
        }
        .links a:hover {
            text-decoration: underline;
        }
        .error {
            color: #ff0000;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h2>Welcome to Our App</h2>
    </header>
    <?php
    session_start();
    ?>
    <div class="container">
        <!-- <img src="../icon.png" alt="Logo"> -->
        <h1>SIGN IN HERE</h1>
        <form action="login_process.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="SIGN IN HERE">
        </form>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>
        <div class="links">
            <a href="forgot_password.php">Forgot Login?</a>
            <a href="register.php">Register</a>
        </div>
        <div class="sponsors">
            <img src="../fnb foundation.png" alt="FNB">
            <img src="../icon.png" alt="Logo">
            <img src="../ki.png" alt="KI">
        </div>
    </div>
    <footer>
        <p>&copy; 2025 Kwakhwa Indvodza. All rights reserved.</p>
    </footer>
</body>
</html>
