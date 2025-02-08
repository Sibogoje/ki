<?php
include '../session.php';
include('../con.php');
$conn = new Con();
$db = $conn->connect();
// Get the chat_id from the request
$chat_id = $_GET['chat_id'];

// Fetch the latest messages
$sql = "SELECT m.message_id, m.message_text, m.sent_at, u.name AS sender_name
        FROM messages m
        JOIN users u ON m.sender_id = u.user_id
        WHERE m.chat_id = '$chat_id'
        ORDER BY m.sent_at ASC";
$result = $db->query($sql);

// Convert messages to JSON
$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
echo json_encode($messages);
?>
