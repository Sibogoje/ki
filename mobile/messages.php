<?php
include '../zon.php';
$conn = new Con();
$db = $conn->connect();
session_start();

$phone = $_SESSION['phone_number'] ?? '';
$userID = $_SESSION['user_id'] ?? null;

// Fetch chat list for the logged-in user
$chats = [];
if ($userID) {
    $query = "
        SELECT pc.chat_id, u.name AS chat_with_user, MAX(m.sent_at) AS last_message_time
        FROM private_chats pc
        JOIN users u ON (u.user_id = pc.user1_id OR u.user_id = pc.user2_id) AND u.user_id != ?
        LEFT JOIN messages m ON m.chat_id = pc.chat_id
        WHERE pc.user1_id = ? OR pc.user2_id = ?
        GROUP BY pc.chat_id, chat_with_user
        ORDER BY last_message_time DESC
    ";
    $stmt = $db->prepare($query);
    $stmt->bind_param('iii', $userID, $userID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $chats[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../styles.css">
    <link rel="manifest" href="../manifest.json">
    <link rel="icon" href="../icon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background-color: black;
            display: flex;
            align-items: center;
            padding: 10px;
            color: green;
        }
        .back-button {
            color: green;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .back-button::before {
            content: '<';
            font-weight: bold;
            margin-right: 5px;
        }
        header h2 {
            margin: 0;
            font-size: 16px;
            color: green;
            flex-grow: 1;
            text-align: center;
        }
        .main2 {
            margin-top: 80px; /* Adjust this value to ensure content appears below the header */
            margin-bottom: 10px; /* Add margin to ensure content is not hidden behind the footer */
            width: 100%; /* Make the main container full width */
            color: black; /* Keep content text color black */
            padding-top: 20px; /* Add padding to the top of the main container */
        }
        .chat-list {
            list-style: none;
            padding: 0;
        }
        .chat-item {
            display: flex;
            align-items: flex-start; /* Align items to the top */
            padding: 10px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
        }
        .chat-item:hover {
            background-color: #f8f9fa;
        }
        .chat-item img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .chat-item .chat-info {
            flex: 1;
        }
        .chat-item .chat-info h5 {
            margin: 0;
            font-size: 16px;
        }
        .chat-item .chat-info p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        .chat-item .last-message-time {
            font-size: 12px;
            color: #999;
        }
        .fab {
            position: fixed;
            bottom: 110px;
            right: 20px;
            z-index: 1000;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: rgb(27, 4, 129);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }
        .modal-dialog {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <header>
        <a href="../index.php" class="back-button py-2">Back</a>
        <h2>A SAFE SPACE FOR YOU</h2>
    </header>
    <!-- Feedback message moved just below the header -->
    <?php
    if (isset($_SESSION['message_feedback'])) {
        echo "<div class='alert alert-info' id='message-feedback'>{$_SESSION['message_feedback']}</div>";
        unset($_SESSION['message_feedback']);
    }
    ?>
    <main class="main2 container-fluid mt-2" style="padding-bottom: 30px; padding-top: 0px;">
        <div class="row">
            <div class="col-12">
                <ul class="chat-list">
                    <?php if (!empty($chats)): ?>
                        <?php foreach ($chats as $chat): ?>
                            <li class="chat-item" onclick="window.location.href='chat.php?chat_id=<?php echo $chat['chat_id']; ?>'">
                                <img src="../user.png" alt="Profile Picture">
                                <div class="chat-info">
                                    <h5><?php echo htmlspecialchars($chat['chat_with_user']); ?></h5>
                                    <p>Last message time: <?php echo htmlspecialchars($chat['last_message_time']); ?></p>
                                </div>
                                <div class="last-message-time">
                                    <?php echo htmlspecialchars($chat['last_message_time']); ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="text-center">No chats found.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </main>

    <!-- Floating Action Button -->
    <div class="fab" data-bs-toggle="modal" data-bs-target="#newMessageModal">
        +
    </div>

    <!-- New Message Modal -->
    <div class="modal fade" id="newMessageModal" tabindex="-1" aria-labelledby="newMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newMessageModalLabel">New Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="newMessageForm" action="send_message.php" method="POST">
                        <div class="mb-3">
                            <label for="recipient_id" class="form-label">To:</label>
                            <select class="form-select" id="recipient_id" name="recipient_id" required>
                                <option value="">Select Recipient</option>
                                <?php
                                // Fetch counselors and admins from the database
                                $query = "SELECT `user_id`, `name`, `surname`, `user_role` FROM `users` WHERE user_role IN ('counselor', 'admin')";
                                $result = $db->query($query);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='{$row['user_id']}'>{$row['name']} {$row['surname']} - {$row['user_role']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php include "../footer.php"; ?> 

    <script>
        // Disappear feedback after 5 seconds
        setTimeout(function() {
            var feedback = document.getElementById('message-feedback');
            if (feedback) {
                feedback.style.display = 'none';
            }
        }, 5000);
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>