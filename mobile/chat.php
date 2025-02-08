<?php
include '../zon.php';
$conn = new Con();
$db = $conn->connect();
session_start();

$userID = $_SESSION['user_id'];
$chatID = $_GET['chat_id'];

// Fetch recipient ID
$query = "
    SELECT CASE 
        WHEN user1_id = ? THEN user2_id 
        ELSE user1_id 
    END AS recipient_id
    FROM private_chats
    WHERE chat_id = ?
";
$stmt = $db->prepare($query);
$stmt->bind_param('ii', $userID, $chatID);
$stmt->execute();
$stmt->bind_result($recipientID);
$stmt->fetch();
$stmt->close();

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="manifest" href="../manifest.json">
    <link rel="icon" href="../icon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .chat-container {
            margin-top: 80px;
            padding: 0 20px; /* Adjust padding to avoid excessive space */
            height: calc(100vh - 160px); /* Adjust height to fit the screen */
            overflow-y: auto;
            width: 100%; /* Make container full width */
        }
        .message {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            width: 100%; /* Make messages full width */
        }
        .message .sender {
            font-weight: bold;
        }
        .message .time {
            font-size: 12px;
            color: #999;
        }
        .new-message-container {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #fff;
            padding: 10px;
            border-top: 1px solid #ddd;
        }
        .new-message-container form {
            display: flex;
        }
        .new-message-container textarea {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
            resize: none; /* Prevent resizing */
            height: 50px; /* Initial height */
        }
        .new-message-container button {
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }
        .loading-indicator {
            display: none;
            margin-left: 10px;
            align-self: center;
            font-size: 16px;
            color: #007bff;
            animation: moveArrow 1s linear infinite;
        }
        @keyframes moveArrow {
            0% { transform: translateX(0); }
            50% { transform: translateX(10px); }
            100% { transform: translateX(0); }
        }
    </style>
</head>
<body>
    <header>
        <h1><a href="messages.php" class="text-white text-decoration-none">&larr;</a> Chat</h1>
    </header>

    <main2 class="chat-container main2" id="chat-container">
        <div class="row">
            <div class="col-12" id="messages">
                <?php if (!empty($messages)): ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="message">
                            <div class="sender"><?php echo htmlspecialchars($message['sender_name']); ?></div>
                            <div class="text"><?php echo htmlspecialchars($message['message_text']); ?></div>
                            <div class="time"><?php echo htmlspecialchars($message['sent_at']); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center">No messages found.</div>
                <?php endif; ?>
            </div>
        </div>
    </main2>

    <div class="new-message-container">
        <form id="new-message-form">
            <input type="hidden" name="chat_id" value="<?php echo $chatID; ?>">
            <input type="hidden" name="recipient_id" value="<?php echo $recipientID; ?>">
            <textarea name="message" placeholder="Type your message here..." required></textarea>
            <button type="submit">Send</button>
            <div class="loading-indicator">Sending &rarr;</div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Send message without refreshing
            $('#new-message-form').on('submit', function(e) {
                e.preventDefault();
                var $form = $(this);
                var $button = $form.find('button');
                var $loadingIndicator = $form.find('.loading-indicator');

                $button.prop('disabled', true);
                $loadingIndicator.show();

                $.ajax({
                    type: 'POST',
                    url: 'send_message.php',
                    data: $form.serialize(),
                    success: function(response) {
                        $form[0].reset();
                        loadMessages();
                    },
                    complete: function() {
                        $button.prop('disabled', false);
                        $loadingIndicator.hide();
                    }
                });
            });

            // Load messages without refreshing
            function loadMessages() {
                $.ajax({
                    url: 'load_messages.php',
                    type: 'GET',
                    data: { chat_id: <?php echo $chatID; ?> },
                    success: function(data) {
                        $('#messages').html(data);
                        $('#chat-container').scrollTop($('#chat-container')[0].scrollHeight);
                    }
                });
            }

            // Poll for new messages every 5 seconds
            setInterval(loadMessages, 5000);
        });
    </script>
</body>
</html>
