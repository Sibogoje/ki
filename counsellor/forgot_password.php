<?php
include 'admin/con.php';
$conn = new Con();
$db = $conn->connect();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);

    // Check if email exists
    $check_sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $db->prepare($check_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Simulate sending a reset link (in a real scenario, generate a token and send via email)
        $reset_link = "http://ki.liquag.com/reset_password.php?email=" . urlencode($email);
        echo "<p style='text-align: center; color: green;'>A password reset link has been sent to your email.</p>";
        // Send the email (placeholder code for now)
        // mail($email, "Password Reset Request", "Click the link to reset your password: $reset_link");
    } else {
        echo "<p style='text-align: center; color: red;'>No account found with this email.</p>";
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
    <title>Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        p {
            color: #555;
            margin-bottom: 20px;
        }
        form {
            margin-top: 20px;
        }
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            margin-bottom: 15px;
        }
        input[type="submit"] {
            width: 100%;
            background: #6a11cb;
            color: #fff;
            border: none;
            font-weight: 600;
            cursor: pointer;
            padding: 12px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        input[type="submit"]:hover {
            background: #2575fc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <p>Enter your registered email to receive a reset link.</p>
        <form action="" method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="submit" value="Send Reset Link">
        </form>
    </div>
</body>
</html>
