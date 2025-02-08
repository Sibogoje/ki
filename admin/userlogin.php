<?php
header('Content-Type: application/json');

// Database connection details
$servername = "localhost";
$username = "u747325399_khwakha";
$password = "u7473kwakha";
$dbname = "u747325399_kwakha";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed."]));
}

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);
$user = $data['username'];
$pass = $data['password'];

// Validate credentials (Replace with your actual validation logic)
$sql = "SELECT * FROM users WHERE username = ? AND password_hash = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user, $pass);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows > 0) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid User credentials"]);
}

$stmt->close();
$conn->close();
?>
