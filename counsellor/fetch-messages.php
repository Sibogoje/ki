<?php
include 'admin/con.php';
$conn = new Con();
$db = $conn->connect();
session_start();

$userID = $_SESSION['counselor_id'];
$chatID = $_GET['chat_id'];

// Fetch messages for the chat
$sql = "SELECT m.message_text, m.sent_at, u.name AS sender_name
        FROM messages m
        JOIN users u ON m.sender_id = u.user_id
        WHERE m.chat_id = ?
        ORDER BY m.sent_at ASC";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $chatID);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<div class='message'>";
    echo "<strong>" . $row['sender_name'] . ":</strong> " . nl2br(htmlspecialchars($row['message_text'])) . "<br>";
    echo "<span class='text-muted'>" . date('F j, Y, g:i a', strtotime($row['sent_at'])) . "</span>";
    echo "</div>";
}

$stmt->close();
$db->close();
?>
