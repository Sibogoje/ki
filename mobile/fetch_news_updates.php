<?php
session_start();
include '../zon.php';
$conn = new Con();
$db = $conn->connect();

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$sql = "SELECT id, title, short_description, image_url FROM news_updates ORDER BY created_at DESC";
$result = $db->query($sql);

$news_updates = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $news_updates[] = $row;
    }
}

$db->close();

header('Content-Type: application/json');
echo json_encode($news_updates);
?>
