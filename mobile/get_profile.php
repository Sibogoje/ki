<?php
include('../con.php');
$conn = new Con();
$db = $conn->connect();

// Get phone parameter
$phone = $_GET['phone'] ?? '';

$sql = "SELECT 
            surname, name, email, phone_number, created_at, password_hash, town, region, 
            age, marital, gender, education, orphan, disability, constituency, community 
        FROM users 
        WHERE phone_number = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $phone);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode([]);
}

$stmt->close();
$db->close();
?>
