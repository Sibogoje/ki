<?php

include('../zon.php');
$con = new Con();
$conn = $con->connect();

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if ($data && is_array($data)) {
    $phoneNumber = isset($data['phone_number']) ? mysqli_real_escape_string($conn, $data['phone_number']) : '';
    $password = isset($data['password']) ? mysqli_real_escape_string($conn, $data['password']) : '';

    if (empty($phoneNumber) || empty($password)) {
        $response = array('success' => false, 'message' => 'Missing fields');
        echo json_encode($response);
        exit;
    }

    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE phone_number = ?");
    $stmt->bind_param("s", $phoneNumber);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();

    if (password_verify($password, $hashedPassword)) {
        $response = array('success' => true, 'message' => 'Login successful');
    } else {
        $response = array('success' => true, 'message' => 'Invalid phone number or password');
    }
    echo json_encode($response);
    exit;
}

?>