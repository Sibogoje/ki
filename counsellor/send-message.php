<?php
include 'admin/con.php';
$conn = new Con();
$db = $conn->connect();
session_start();

$userID = $_SESSION['counselor_id'];
$chatID = $_POST['chat_id'];
$messageText = htmlspecialchars($_POST['message_text'], ENT_QUOTES, 'UTF-8');

// Determine the receiver ID
$sql = "SELECT CASE WHEN user1_id = ? THEN user2_id ELSE user1_id END AS receiver_id
        FROM private_chats
        WHERE chat_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('ii', $userID, $chatID);
$stmt->execute();
$stmt->bind_result($receiverID);
$stmt->fetch();
$stmt->close();

// Insert message
$sql = "INSERT INTO messages (chat_id, sender_id, receiver_id, message_text) VALUES (?, ?, ?, ?)";
$stmt = $db->prepare($sql);
$stmt->bind_param('iiis', $chatID, $userID, $receiverID, $messageText);

if ($stmt->execute()) {
    echo "<script>window.location.href='view-chat.php?chat_id=$chatID';</script>";
} else {
    echo "<script>alert('Error sending message. Please try again.'); window.location.href='view-chat.php?chat_id=$chatID';</script>";
}

$stmt->close();
$db->close();
?>
