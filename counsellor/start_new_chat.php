<?php
include 'admin/con.php';
$conn = new Con();
$db = $conn->connect();
session_start();

$userID = $_SESSION['counselor_id'];
$chatWithUserID = $_POST['user_id'];
$messageText = htmlspecialchars($_POST['message_text'], ENT_QUOTES, 'UTF-8');

// Check if a chat already exists
$sql = "SELECT chat_id FROM private_chats WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)";
$stmt = $db->prepare($sql);
$stmt->bind_param('iiii', $userID, $chatWithUserID, $chatWithUserID, $userID);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Chat exists, fetch chat_id
    $stmt->bind_result($chatID);
    $stmt->fetch();
} else {
    // Create a new chat
    $sql = "INSERT INTO private_chats (user1_id, user2_id) VALUES (?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('ii', $userID, $chatWithUserID);
    $stmt->execute();
    $chatID = $stmt->insert_id;
}

$stmt->close();

// Insert the initial message
$sql = "INSERT INTO messages (chat_id, sender_id, receiver_id, message_text) VALUES (?, ?, ?, ?)";
$stmt = $db->prepare($sql);
$stmt->bind_param('iiis', $chatID, $userID, $chatWithUserID, $messageText);

if ($stmt->execute()) {
    echo "<script>window.location.href='view-chat.php?chat_id=$chatID';</script>";
} else {
    echo "<script>alert('Error starting chat. Please try again.'); window.location.href='index.php';</script>";
}

$stmt->close();
$db->close();
?>
