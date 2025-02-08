<?php
include('../con.php');
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new Con();
$db = $conn->connect();

// Get chat ID from the URL
if (!isset($_GET['chat_id']) || empty($_GET['chat_id'])) {
    die("Chat ID is missing.");
}

$chatID = $_GET['chat_id'];

// Fetch messages for the given chat ID
$stmt = $db->prepare("
    SELECT m.message_id, m.sender_id, m.receiver_id, m.message_text, m.sent_at, 
           u1.name AS sender_name, u2.name AS receiver_name
    FROM messages m
    JOIN users u1 ON m.sender_id = u1.user_id
    JOIN users u2 ON m.receiver_id = u2.user_id
    WHERE m.chat_id = ? 
    ORDER BY m.sent_at ASC
");
$stmt->bind_param("i", $chatID);
$stmt->execute();
$messagesResult = $stmt->get_result();

if ($messagesResult->num_rows === 0) {
    echo "No messages found for this chat.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .chat-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100vh; /* Full viewport height */
        }
        .chat-header {
            background: #007bff;
            color: #ffffff;
            padding: 10px;
            text-align: center;
        }
        .chat-messages {
            flex: 1; /* Make the message area flexible */
            padding: 10px;
            max-height: 400px;
            overflow-y: auto;
            height: 400px; /* Added height to enable scrolling */
            width: 100%; /* Ensure full width */
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            background: #e9ecef;
            width: 100%; /* Make messages full-width */
            box-sizing: border-box; /* Ensure padding doesn't affect width */
        }
        .message.sent {
            background: #d4edda;
            text-align: right;
        }
        .message.received {
            background: #f8d7da;
        }
        .chat-footer {
            padding: 20px; /* Increased padding for the footer */
            background: #f4f4f9;
            border-top: 1px solid #ccc;
        }
        .chat-footer form {
            display: flex;
            gap: 10px;
        }
        .chat-footer input[type="text"] {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .chat-footer button {
            padding: 8px 16px;
            background: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .chat-footer button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="chat-container">

    <div id="chat-messages" class="chat-messages">
        <?php while ($message = $messagesResult->fetch_assoc()): ?>
            <div class="message <?php echo ($message['sender_id'] == $message['receiver_id']) ? 'sent' : 'received'; ?>" id="message-<?php echo $message['message_id']; ?>">
                <strong><?php echo ($message['sender_id'] == $message['receiver_id']) ? 'You' : htmlspecialchars($message['sender_name']); ?>:</strong>
                <p><?php echo htmlspecialchars($message['message_text']); ?></p>
                <small><?php echo date("d M Y, h:i A", strtotime($message['sent_at'])); ?></small>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="chat-footer">
        <form id="send-message-form" method="POST">
            <input type="hidden" name="chat_id" value="<?php echo $chatID; ?>">
            <input type="text" name="message_text" id="message_text" placeholder="Type your message..." required>
            <button type="submit">Send</button>
        </form>
    </div>
</div>

<script>
    // Function to send a message via AJAX
    document.getElementById('send-message-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const messageText = document.getElementById('message_text').value;
        const chatID = document.querySelector('input[name="chat_id"]').value;

        const formData = new FormData();
        formData.append('chat_id', chatID);
        formData.append('message_text', messageText);

        fetch('send-message.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear the input field and append the new message
                document.getElementById('message_text').value = '';
                appendMessage(data.message);
            } else {
                alert('Failed to send the message');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Function to append the new message to the chat
    function appendMessage(message) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', message.sender_id === message.receiver_id ? 'sent' : 'received');
        messageDiv.innerHTML = `
            <strong>${message.sender_name}:</strong>
            <p>${message.message_text}</p>
            <small>${message.sent_at}</small>
        `;
        document.getElementById('chat-messages').appendChild(messageDiv);
        scrollToBottom();
    }

    // Function to auto-scroll to the bottom of the chat
    function scrollToBottom() {
        const chatMessages = document.getElementById('chat-messages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Polling function to fetch new messages
    setInterval(function() {
        const chatID = document.querySelector('input[name="chat_id"]').value;
        fetch('fetch-messages.php?chat_id=' + chatID)
        .then(response => response.json())
        .then(data => {
            if (data.newMessages.length > 0) {
                data.newMessages.forEach(message => {
                    appendMessage(message);
                });
            }
        })
        .catch(error => console.error('Error fetching new messages:', error));
    }, 3000); // Poll every 3 seconds
</script>

</body>
</html>

