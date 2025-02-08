<?php
include('../con.php');
session_start();

$conn = new Con();
$db = $conn->connect();

if (!isset($_GET['phone'])) {
    die("Phone number not provided.");
}

$phone = $_GET['phone'];



// Use prepared statements to avoid SQL injection
$stmt = $db->prepare("SELECT user_id FROM users WHERE phone_number = ?");
$stmt->bind_param("s", $phone);
$stmt->execute();
$phoneresult = $stmt->get_result();

if ($phoneresult->num_rows == 0) {
    echo "No user found with this phone number.";
} else {
    while ($phonerow = $phoneresult->fetch_assoc()) {
        $userID = $phonerow['user_id'];
        $_SESSION['user_id1'] = $phonerow['user_id'];
     //   echo $userID;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messaging</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-card {
            margin-bottom: 1rem;
        }
        .chat-list-container {
            max-height: 500px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-between mx-0 p-3">
        <div class="col-md-12 text-center">

            <!-- New Message Button -->
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#newMessageModal">New Message</button>

            <!-- PHP Logic to Fetch Chat Data -->
            <?php
            $sql = "SELECT pc.chat_id, u.name AS chat_with_user, MAX(m.sent_at) AS last_message_time
                    FROM private_chats pc
                    JOIN users u ON (u.user_id = pc.user1_id OR u.user_id = pc.user2_id) AND u.phone_number != '$userID'
                    LEFT JOIN messages m ON m.chat_id = pc.chat_id
                    WHERE pc.user1_id = '$userID' OR pc.user2_id = '$userID'
                    GROUP BY pc.chat_id, chat_with_user
                    ORDER BY last_message_time DESC";

            $result = $db->query($sql);
            ?>

            <!-- Chat List -->
            <div class="chat-list-container">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="card chat-card">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['chat_with_user']); ?></h5>
                                <p class="card-text text-muted">
                                    Last message: <?= $row['last_message_time'] ?? 'No messages yet'; ?>
                                </p>
                                <a href="view-chat.php?chat_id=<?= $row['chat_id']; ?>" class="btn btn-primary btn-sm">View Chat</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">No chats found.</p>
                <?php endif; ?>
            </div>

            <!-- Modal for New Message -->
            <div class="modal fade" id="newMessageModal" tabindex="-1" aria-labelledby="newMessageModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newMessageModalLabel">New Message</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="start_new_chat.php?userID=<?php echo $userID; ?>" method="post">
                                <div class="mb-3">
                                    <label for="selectUser" class="form-label">Select User</label>
                                    <select class="form-select" id="selectUser" name="user_id" required>
                                        <option value="" disabled selected>Select a user</option>
                                        <?php
                                        $users_sql = "SELECT user_id, name, user_role FROM users WHERE user_id != '$userID' AND user_role != 'client'";
                                        $users_result = $db->query($users_sql);
                                        while ($user = $users_result->fetch_assoc()) {
                                            echo '<option value="' . $user['user_id'] . '">' . htmlspecialchars($user['name']) . " - " . htmlspecialchars($user['user_role']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="messageText" class="form-label">Message</label>
                                    <textarea class="form-control" id="messageText" name="message_text" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Send</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal -->

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
