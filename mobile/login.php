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
        echo json_encode(['success' => false, 'message' => 'Missing fields', 'error' => true]);
        exit;
    }

    $stmt = $conn->prepare("SELECT password_hash, status FROM users WHERE phone_number = ?");
    $stmt->bind_param("s", $phoneNumber);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword, $status);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            if ($status == 1) {
                echo json_encode(['success' => true, 'message' => 'Login successful', 'error' => false]);
            } elseif ($status == 0) {
                echo json_encode(['success' => false, 'message' => 'Account not activated. Contact Kwakha Indvodza.', 'error' => true]);
            } elseif ($status == 2) {
                echo json_encode(['success' => false, 'message' => 'Account blocked. Contact Kwakha Indvodza.', 'error' => true]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid phone number or password', 'error' => true]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found', 'error' => true]);
    }

    $stmt->close();
    $conn->close();
    exit;
}


?>