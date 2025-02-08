<?php
include '../session.php';
include('../con.php');
$conn = new Con();
$db = $conn->connect();

header('Content-Type: application/json'); // Set JSON header

$userID = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $chat_id = $_POST['chat_id'];
    $message_text = $_POST['message_text'];

    // Find the receiver
    $sql = "SELECT CASE WHEN user1_id = ? THEN user2_id ELSE user1_id END AS receiver_id
            FROM private_chats
            WHERE chat_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('ii', $userID, $chat_id);
    $stmt->execute();
    $stmt->bind_result($receiver_id);
    $stmt->fetch();
    $stmt->close();

    // Insert the new reply
    $sql = "INSERT INTO messages (chat_id, sender_id, receiver_id, message_text, sent_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('iiis', $chat_id, $userID, $receiver_id, $message_text);

    if ($stmt->execute()) {
        // Fetch the message details for the response
        $response = [
            'success' => true,
            'message_text' => $message_text,
            'sent_at' => date('Y-m-d H:i:s'), // Or use a formatted date
            'sender_name' => $_SESSION['name'] // Replace with sender's name
        ];
        echo json_encode($response);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
}

$db->close();
?>
