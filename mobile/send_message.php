<?php
include '../con.php';
$conn = new Con();
$db = $conn->connect();
session_start();

$userID = $_SESSION['user_id'];
$recipientID = $_POST['recipient_id'] ?? null;
$messageText = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');

if ($recipientID) {
    // Check if a chat already exists between the two users
    $sql = "SELECT chat_id FROM private_chats WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('iiii', $userID, $recipientID, $recipientID, $userID);
    $stmt->execute();
    $stmt->bind_result($chatID);
    $stmt->fetch();
    $stmt->close();

    if (!$chatID) {
        // Create a new chat if it doesn't exist
        $sql = "INSERT INTO private_chats (user1_id, user2_id) VALUES (?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('ii', $userID, $recipientID);
        $stmt->execute();
        $chatID = $stmt->insert_id;
        $stmt->close();
    }

    // Insert the message
    $sql = "INSERT INTO messages (chat_id, sender_id, receiver_id, message_text) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('iiis', $chatID, $userID, $recipientID, $messageText);

    if ($stmt->execute()) {
        $_SESSION['message_feedback'] = "Message sent successfully!";
    } else {
        $_SESSION['message_feedback'] = "Error sending message. Please try again.";
    }

    $stmt->close();
} else {
    $_SESSION['message_feedback'] = "Recipient not selected. Please try again.";
}

$db->close();

header("Location: messages.php");
exit();
?>
