<?php 
include 'admin/con.php';
$conn = new Con();
$db = $conn->connect();

// Start a session
session_start();

$userID = $_SESSION['counselor_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Messaging</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Header -->
        <?php include 'header.php'; ?>

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar -->
            <?php include 'navbar.php'; ?>
            
            
        <div class="container-fluid pt-4 px-4" >
            <div class="row bg-light rounded align-items-center justify-content-between mx-0" style="padding: 20px;">
                    <div class="col-md-12 text-center">
            <!-- Inbox Section -->
            <?php
            $sql = "SELECT pc.chat_id, u.name AS chat_with_user, MAX(m.sent_at) AS last_message_time
                    FROM private_chats pc
                    JOIN users u ON (u.user_id = pc.user1_id OR u.user_id = pc.user2_id) AND u.user_id != '$userID'
                    LEFT JOIN messages m ON m.chat_id = pc.chat_id
                    WHERE pc.user1_id = '$userID' OR pc.user2_id = '$userID'
                    GROUP BY pc.chat_id, chat_with_user
                    ORDER BY last_message_time DESC";
            $result = $db->query($sql);
            ?>

           
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Inbox</h5>

                                <!-- Chats Table -->
                                <table class="table datatable">
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
                                <!-- End Chats Table -->
                            </div>
                        </div>
                    </div>



           
        </div>
        </div>
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
                <form action="start_new_chat.php" method="post">
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


        
        
         <?php include 'footer.php'; ?>
        <!-- Content End -->

        <!-- Back to Top -->
        <!--<a href="#" class="btn btn-lg btn-primary btn-lg-square "><i class="bi bi-arrow-up"></i></a>-->
        
                    <!-- Floating Button for New Message -->
<button type="button" class="btn btn-primary btn-float" data-bs-toggle="modal" data-bs-target="#newMessageModal" style="position: fixed; bottom: 20px; right: 140px; z-index: 1000;">
    <i class="fas fa-comment-dots"></i>
</button>


            <!-- Footer -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>
