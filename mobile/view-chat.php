<?php
include '../admin/zon.php';
$conn = new Con();
$db = $conn->connect();
session_start();

$userID = $_SESSION['user_id1'];
$chatID = $_GET['chat_id'];

// Fetch initial messages for the chat
$sql = "SELECT m.message_text, m.sent_at, u.name AS sender_name
        FROM messages m
        JOIN users u ON m.sender_id = u.user_id
        WHERE m.chat_id = ?
        ORDER BY m.sent_at ASC";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $chatID);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>View Message</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Chat box container styling */
        #chat-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            height: calc(100vh - 140px); /* Adjust height to fit the viewport */
            overflow-y: scroll;
            margin-bottom: 70px; /* Space for the fixed form */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .chat-message {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            width: 100%;
        }

        .chat-message .avatar {
            width: 40px;
            height: 40px;
            margin-right: 10px;
            border-radius: 50%;
        }

        .chat-message .message-content {
            background-color: #ffffff;
            padding: 10px;
            border-radius: 10px;
            max-width: 70%;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .chat-message .message-content::after {
            content: "";
            position: absolute;
            top: 10px;
            left: -10px;
            width: 100;
            height: 0;
            border-top: 10px solid transparent;
            border-right: 10px solid #ffffff;
            border-bottom: 10px solid transparent;
        }

        .chat-message.sender .message-content {
            background-color: #dcf8c6;
        }

        .chat-message.sender .message-content::after {
            left: auto;
            right: -10px;
            border-right-color: transparent;
            border-left: 10px solid #dcf8c6;
        }

        .text-muted {
            display: block;
            font-size: 12px;
            margin-top: 5px;
            text-align: right;
        }

        /* Add responsive styling */
        @media (max-width: 576px) {
            #chat-box {
                height: calc(100vh - 140px);
            }

            .chat-message .message-content {
                font-size: 12px;
                width: 100%;
            }

            .text-muted {
                font-size: 10px;
            }
        }

        /* Fix the form at the bottom */
        #message-form {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: white;
            padding: 10px;
            border-top: 1px solid #ccc;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container-fluid pt-2 px-2">
        <div class="row bg-light rounded align-items-center justify-content-center mx-0" style="padding: 5px;">
            <div class="col-md-12">
               
                <div class="chat-box mb-4" id="chat-box">
                    <?php while ($row = $result->fetch_assoc()) {
                        $isSender = ($row['m.sender_id'] == $_SESSION['client_user_id']); // Replace 'sender_id' with your column for sender's ID
                        $alignmentClass = $isSender ? 'sender' : ''; ?>
                        <div class="chat-message <?php echo $alignmentClass; ?>">

                            <div class="message-content">
                                <strong><?php echo htmlspecialchars($row['sender_name']); ?>:</strong>
                                <div><?php echo nl2br(htmlspecialchars($row['message_text'])); ?></div>
                                <span class="text-muted small">
                                    <?php echo date('F j, Y, g:i a', strtotime($row['sent_at'])); ?>
                                </span>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <form action="send-message.php" method="post" id="message-form">
        <input type="hidden" name="chat_id" value="<?php echo $chatID; ?>">
        <div class="input-group">
            <input type="text" name="message_text" class="form-control" placeholder="Type your message..." required>
            <button class="btn btn-primary" type="submit">Send</button>
        </div>
    </form>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let lastMessageID = 0;

        function fetchMessages() {
            $.ajax({
                url: 'fetch-messages.php',
                method: 'GET',
                data: { chat_id: <?php echo $chatID; ?> },
                success: function(response) {
                    try {
                        const data = typeof response === 'object' ? response : JSON.parse(response);
                        if (data.error) {
                            console.error('Error:', data.error);
                            return;
                        }

                        const messages = data.messages;
                        if (messages.length > 0 && messages[messages.length - 1].message_id !== lastMessageID) {
                            let chatHTML = '';
                            messages.forEach(message => {
                                const isCurrentUser = message.sender_name === '<?php echo $_SESSION['user_name']; ?>';
                                const alignment = isCurrentUser ? 'sender' : '';
                                const avatar = '<img src="https://via.placeholder.com/40" alt="Avatar">';

                                chatHTML += `
                                    <div class="w-100 chat-message ${alignment}">
                                        
                                        <div class="message-content">
                                            <strong>${message.sender_name}</strong><br>
                                            ${message.message_text}<br>
                                            <small class="text-muted">${new Date(message.sent_at).toLocaleString()}</small>
                                        </div>
                                    </div>`;
                            });

                            $('#chat-box').html(chatHTML);
                            lastMessageID = messages[messages.length - 1].message_id;

                            const chatBox = document.getElementById('chat-box');
                            chatBox.scrollTop = chatBox.scrollHeight;
                        }
                    } catch (error) {
                        console.error('Error parsing response:', error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        }

        $(document).ready(function() {
            //location.reload(true);
            fetchMessages();
            setInterval(fetchMessages, 3000);

            $('#message-form').submit(function(event) {
                event.preventDefault();

                $.ajax({
                    url: 'send-message.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function() {
                        fetchMessages();
                        $('#message-form')[0].reset();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error sending message:', error);
                    }
                });
            });
        });
    </script>
</body>
</html>
