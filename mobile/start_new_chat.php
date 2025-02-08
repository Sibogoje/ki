<?php
include('../zon.php');
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new Con();
$db = $conn->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_GET['userID']; 
    $receiverID = $_POST['user_id'];
    $messageText = $_POST['message_text'];

    if (empty($userID) || empty($receiverID) || empty($messageText)) {
        die("Sender, receiver, or message text is missing.");
    }

    // Validate sender and receiver
    $userCheckStmt = $db->prepare("SELECT user_id FROM users WHERE user_id = ?");
    $userCheckStmt->bind_param("i", $userID);
    $userCheckStmt->execute();
    if ($userCheckStmt->get_result()->num_rows === 0) {
        die("Invalid sender ID.");
    }

    $receiverCheckStmt = $db->prepare("SELECT user_id FROM users WHERE user_id = ?");
    $receiverCheckStmt->bind_param("i", $receiverID);
    $receiverCheckStmt->execute();
    if ($receiverCheckStmt->get_result()->num_rows === 0) {
        die("Invalid receiver ID.");
    }

    // Check for existing chat
    $stmt = $db->prepare("
        SELECT chat_id 
        FROM private_chats 
        WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)
    ");
    $stmt->bind_param("iiii", $userID, $receiverID, $receiverID, $userID);
    $stmt->execute();
    $chatResult = $stmt->get_result();

    if ($chatResult->num_rows > 0) {
        $chat = $chatResult->fetch_assoc();
        $chatID = $chat['chat_id'];
    } else {
        // Insert new chat
        $insertChatStmt = $db->prepare("INSERT INTO private_chats (user1_id, user2_id) VALUES (?, ?)");
        $insertChatStmt->bind_param("ii", $userID, $receiverID);
        if ($insertChatStmt->execute()) {
            $chatID = $insertChatStmt->insert_id;
        } else {
            die("Failed to create new chat: " . $insertChatStmt->error);
        }
    }

    // Insert first message
    $insertMessageStmt = $db->prepare("
        INSERT INTO messages (chat_id, sender_id, receiver_id, message_text, sent_at) 
        VALUES (?, ?, ?, ?, NOW())
    ");
    $insertMessageStmt->bind_param("iiis", $chatID, $userID, $receiverID, $messageText);

    if ($insertMessageStmt->execute()) {
        header("Location: view-chat.php?chat_id=$chatID");
        exit();
    } else {
        die("Failed to send message: " . $insertMessageStmt->error);
    }
} else {
    die("Invalid request method.");
}
?>
