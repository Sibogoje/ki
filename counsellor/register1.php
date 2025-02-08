<?php
include 'admin/con.php';

$conn = new Con();
$db = $conn->connect();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('log_errors', 1);
ini_set('error_log', 'register-log.log');

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $name = htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8'); 
   $surname = htmlspecialchars($_POST['surname'] ?? '', ENT_QUOTES, 'UTF-8'); 
   $region = htmlspecialchars($_POST['region'] ?? '', ENT_QUOTES, 'UTF-8'); 
   $town = htmlspecialchars($_POST['town'] ?? '', ENT_QUOTES, 'UTF-8'); 
   $email = htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); 
   $phone = htmlspecialchars($_POST['phone'] ?? '', ENT_QUOTES, 'UTF-8'); 
   $username = htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8'); 
   $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email or phone already exists
    $check_sql = "SELECT email, phone_number FROM users WHERE email = ? OR phone_number = ?";
    if ($check_stmt = $db->prepare($check_sql)) {
        $check_stmt->bind_param("ss", $email, $phone);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            echo "Email or phone already registered.";
        } else {
            // Insert new user
            $user_role = "client";
            $created = date('Y-m-d H:i:s');
            $sql = "INSERT INTO users (name, surname, region, town, email, phone_number, counselor_username, password_hash, user_role, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            if ($stmt = $db->prepare($sql)) {
                $stmt->bind_param("ssssssssss", $name, $surname, $region, $town, $email, $phone, $username, $password, $user_role, $created);
                if ($stmt->execute()) {
                    echo "Registration successful!";
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
            }
        }
        $check_stmt->close();
    } else {
        echo "Database query error: " . $db->error;
    }

    $db->close();
}
?>
