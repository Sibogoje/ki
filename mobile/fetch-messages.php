<?php
include '../admin/con.php';
$conn = new Con();
$db = $conn->connect();
session_start();

// Ensure the content type is JSON
header('Content-Type: application/json; charset=utf-8');

// Start session for user data
session_start();

// Fetch user and chat details
$userID = $_SESSION['user_id1'];
$chatID = isset($_GET['chat_id']) ? (int) $_GET['chat_id'] : 0;

// Validate chat ID
if ($chatID <= 0) {
    echo json_encode(['error' => 'Invalid chat ID']);
    exit;
}

// Fetch messages from the database
$sql = "SELECT m.message_id, m.message_text, m.sent_at, u.name AS sender_name
        FROM messages m
        JOIN users u ON m.sender_id = u.user_id
        WHERE m.chat_id = ?
        ORDER BY m.sent_at ASC";

$stmt = $db->prepare($sql);
$stmt->bind_param('i', $chatID);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        'message_id' => $row['message_id'],
        'message_text' => htmlspecialchars($row['message_text']),
        'sent_at' => $row['sent_at'],
        'sender_name' => htmlspecialchars($row['sender_name']),
    ];
}

// Ensure valid JSON is sent
if (json_last_error() === JSON_ERROR_NONE) {
    echo json_encode(['messages' => $messages]);
} else {
    echo json_encode(['error' => 'JSON encoding error: ' . json_last_error_msg()]);
}
exit;
?>