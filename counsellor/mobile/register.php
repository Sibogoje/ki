<?php

include('../zon.php');
$con = new Con();
$conn = $con->connect();

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);
error_log(print_r($data, true));


$logFile = 'register_log.txt';
$logData = date('Y-m-d H:i:s') . ' - ' . print_r($data, true) . PHP_EOL;
file_put_contents($logFile, $logData, FILE_APPEND);

if ($data && is_array($data)) {
    $name = isset($data['name']) ? mysqli_real_escape_string($conn, $data['name']) : '';
    $surname = isset($data['surname']) ? mysqli_real_escape_string($conn, $data['surname']) : '';
    $email = isset($data['email']) ? mysqli_real_escape_string($conn, $data['email']) : '';
    $phoneNumber = isset($data['phone_number']) ? mysqli_real_escape_string($conn, $data['phone_number']) : '';
    $passwordHash = isset($data['password_hash']) ? mysqli_real_escape_string($conn, $data['password_hash']) : '';
    $userRole = isset($data['user_role']) ? mysqli_real_escape_string($conn, $data['user_role']) : '';

    // Check phone number uniqueness
    $stmt = $conn->prepare("SELECT * FROM users WHERE phone_number = ? OR email = ? ");
    $stmt->bind_param("ss", $phoneNumber, $email);
    $stmt->execute();
    $stmt->store_result();
    $count = $stmt->num_rows;

    if ($count > 0) {
        $response = array('success' => false, 'message' => 'Account already exists');
        echo json_encode($response);
        exit;
    }

    // Registration logic
    if (!empty($name) && !empty($surname) && !empty($email) && !empty($phoneNumber) && !empty($passwordHash) && !empty($userRole)) {
        $stmt = $conn->prepare("INSERT INTO users (name, surname, email, phone_number, password_hash, user_role, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssss", $name, $surname, $email, $phoneNumber, $passwordHash, $userRole);

        if ($stmt->execute()) {
            $response = array('success' => true, 'message' => 'Registration successful', 'clear_fields' => true);
        } else {
            $response = array('success' => false, 'message' => 'Registration failed');
        }
        echo json_encode($response);
        exit;
    } else {
        $response = array('success' => false, 'message' => 'Missing fields');
        echo json_encode($response);
        exit;
    }
}

$conn->close();

?>