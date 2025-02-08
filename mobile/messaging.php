<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Inbox</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"> 
    <style>
        /* CSS Styles */
        body {
            font-family: sans-serif;
        }

        #spinner {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        @media (max-width: 768px) {
            /* Adjust table styles for smaller screens */
            table th, table td {
                font-size: 0.8rem;
                padding: 0.5rem;
            }
        }
    </style>
</head>

<body>

    <div id="spinner" class="show">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

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
        exit; // Exit after displaying the message
    } else {
        $row = $phoneresult->fetch_assoc(); // Fetch only the first row
        $userID = $row['user_id'];
        $_SESSION['user_id1'] = $userID;
       
    }

    // Fetch chat data for the authenticated user
    $sql = "SELECT pc.chat_id, u.name AS chat_with_user, MAX(m.sent_at) AS last_message_time
            FROM private_chats pc
            JOIN users u ON (u.user_id = pc.user1_id OR u.user_id = pc.user2_id) AND u.user_id != '$userID'
            LEFT JOIN messages m ON m.chat_id = pc.chat_id
            WHERE pc.user1_id = '$userID' OR pc.user2_id = '$userID'
            GROUP BY pc.chat_id, chat_with_user
            ORDER BY last_message_time DESC";
    $result = $db->query($sql);
    ?>

    <div class="container-fluid p-2">
        <div class="row bg-light rounded align-items-center justify-content-center mx-0" style="padding: 5px;">
            <div class="col-md-12 text-center">
                

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive"> 
                            <table class="table"> 
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Chat With</th>
                                        <th scope="col">Last Message Time</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()) : ?>
                                        <tr>
                                            <th scope="row"><?= $row['chat_id']; ?></th>
                                            <td><?= $row['chat_with_user']; ?></td>
                                            <td><?= $row['last_message_time']; ?></td>
                                            <td>
                                                <a href="view-chat.php?chat_id=<?= $row['chat_id']; ?>" class="btn btn-primary btn-sm">View</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                                // Fetch users to chat with
                                $users_sql = "SELECT user_id, name, user_role FROM users WHERE user_id != '$userID' AND user_role != 'client' ";
                                $users_result = $db->query($users_sql);
                                while ($user = $users_result->fetch_assoc()) {
                                    echo '<option value="' . $user['user_id'] . '">' . $user['name'] ." - ". $user['user_role'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="messageText" class="form-label">Message</label>
                            <textarea class="form-control" id="messageText" name="message_text" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-primary btn-float" data-bs-toggle="modal" data-bs-target="#newMessageModal" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
        <i class="fas fa-comment-dots"></i> 
    </button>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Clear browser cache
           // window.location.reload(true); 

            setTimeout(function() {
                $('#spinner').fadeOut('slow');
            }, 500);
        });
    </script>

</body>

</html>