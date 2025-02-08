<?php
include '../zon.php';
$conn = new Con();
$db = $conn->connect();
session_start();

$chatID = $_GET['chat_id'];

// Fetch chat messages
$query = "
    SELECT m.message_text, m.sent_at, u.name AS sender_name
    FROM messages m
    JOIN users u ON m.sender_id = u.user_id
    WHERE m.chat_id = ?
    ORDER BY m.sent_at ASC
";
$stmt = $db->prepare($query);
$stmt->bind_param('i', $chatID);
$stmt->execute();
$result = $stmt->get_result();
$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
$stmt->close();
$db->close();

foreach ($messages as $message) {
    echo '<div class="message">';
    echo '<div class="sender">' . htmlspecialchars($message['sender_name']) . '</div>';
    echo '<div class="text">' . htmlspecialchars($message['message_text']) . '</div>';
    echo '<div class="time">' . htmlspecialchars($message['sent_at']) . '</div>';
    echo '</div>';
}
?>
