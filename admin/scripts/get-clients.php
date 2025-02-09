<?php
include('../zon.php');
$conn = new Con();
$db = $conn->connect();

$sql = "SELECT user_id, name FROM users where user_role = 'client' ";
$result = $db->query($sql);

$clients = [];
while ($row = $result->fetch_assoc()) {
    $clients[] = $row;
}

header('Content-Type: application/json');
echo json_encode($clients);

$db->close();
?>

