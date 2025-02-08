<?php
include '../session.php';
include('../zon.php');
$conn = new Con();
$db = $conn->connect();

$userID = $_SESSION['user_id'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $receiver_id = $_POST['receiver_id'];
    $message_text = $_POST['message_text'];

    // Check if a chat already exists between the users
    $sql = "SELECT chat_id FROM private_chats WHERE 
            (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('iiii', $userID, $receiver_id, $receiver_id, $userID);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Chat exists, get the chat_id
        $stmt->bind_result($chat_id);
        $stmt->fetch();
    } else {
        // Chat doesn't exist, create a new chat
        $stmt->close();
        $sql = "INSERT INTO private_chats (user1_id, user2_id) VALUES (?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('ii', $userID, $receiver_id);
        $stmt->execute();
        $chat_id = $stmt->insert_id;
    }

    // Close the statement
    $stmt->close();

    // Insert the new message
    $sql = "INSERT INTO messages (chat_id, sender_id, receiver_id, message_text, sent_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('iiis', $chat_id, $userID, $receiver_id, $message_text);

    if ($stmt->execute()) {
        // Message sent successfully
        echo "<script>alert('Message sent successfully!'); window.location.href='../messages.php';</script>";
    } else {
        // Error occurred
        echo "<script>alert('Error sending message. Please try again.'); window.location.href='../messages.php';</script>";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$db->close();
?>

