<?php include('zon.php'); $conn = new Con(); $db = $conn->connect(); 
include 'session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Messages</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <style>
      /* CSS for Floating Button */
.btn-float {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
    border-radius: 50%;
    padding: 15px 20px;
    font-size: 18px;
}

  </style>

</head>

<body>

  <!-- ======= Header ======= -->
  <?php include 'header.php'; ?>

  <!-- ======= Main Content ======= -->
  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Messages</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Messages</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    
    <?php

$userID = $_SESSION['user_id'];

// Fetch the chats for the logged-in user
$sql = "SELECT pc.chat_id, u.name AS chat_with_user, MAX(m.sent_at) AS last_message_time
        FROM private_chats pc
        JOIN users u ON (u.user_id = pc.user1_id OR u.user_id = pc.user2_id) AND u.user_id != '$userID'
        LEFT JOIN messages m ON m.chat_id = pc.chat_id
        WHERE pc.user1_id = '$userID' OR pc.user2_id = '$userID'
        GROUP BY pc.chat_id, chat_with_user
        ORDER BY last_message_time DESC";
$result = $db->query($sql);
?>

<section class="section chats">
    <div class="row">
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
                            <?php
                            // Display chats
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<th scope="row">' . $row['chat_id'] . '</th>';
                                echo '<td>' . $row['chat_with_user'] . '</td>';
                                echo '<td>' . $row['last_message_time'] . '</td>';
                                echo '<td>';
                                echo '<a href="view-chat.php?chat_id=' . $row['chat_id'] . '" class="btn btn-primary btn-sm">View</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                    <!-- End Chats Table -->

                </div>
            </div>
        </div>
    </div>
</section>

    
    



<!-- Floating Button for New Message -->
<button type="button" class="btn btn-primary btn-float" data-bs-toggle="modal" data-bs-target="#newMessageModal">
    <i class="fas fa-comment-dots"></i>
</button>



<!-- New Message Modal -->
<div class="modal fade" id="newMessageModal" tabindex="-1" aria-labelledby="newMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newMessageModalLabel">New Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="newMessageForm" method="POST" action="scripts/send-message.php">
                    <div class="mb-3">
                        <label for="receiver" class="form-label">Receiver</label>
                        <select id="receiver" name="receiver_id" class="form-select" required>
                            <?php
                            $users = $db->query("SELECT user_id, name FROM users WHERE user_id != '$userID'");
                            while ($user = $users->fetch_assoc()) {
                                echo '<option value="' . $user['user_id'] . '">' . $user['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="messageText" class="form-label">Message</label>
                        <textarea id="messageText" name="message_text" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send</button>
                </form>
            </div>
        </div>
    </div>
</div>



  </main><!-- End Main Content -->

  <?php 
  include('footer.php');
  ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
