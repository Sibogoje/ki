<?php
// Database connection
include('../zon.php');
$conn = new Con();
$db = $conn->connect();

//$conn = new mysqli($servername, $username, $password, $dbname);



$userID = $_GET ['phone']; // Replace this with the logged-in user's ID

$sql = "SELECT pc.chat_id, u.name AS chat_with_user, MAX(m.sent_at) AS last_message_time
        FROM private_chats pc
        JOIN users u ON (u.user_id = pc.user1_id OR u.user_id = pc.user2_id) AND u.user_id != '$userID'
        LEFT JOIN messages m ON m.chat_id = pc.chat_id
        WHERE pc.user1_id = '$userID' OR pc.user2_id = '$userID'
        GROUP BY pc.chat_id, chat_with_user
        ORDER BY last_message_time DESC";

$result = $db->query($sql);

$chats = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chats[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($chats);

$db->close();
?>
