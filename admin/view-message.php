<?php
include('con.php');
$conn = new Con();
$db = $conn->connect();
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
    .message {
      margin-bottom: 15px;
      padding: 10px;
      border-radius: 10px;
    }
    .message.sent {
      background-color: #ffffff;
      text-align: left;
    }
    .message.received {
      background-color: #f2f2f2;
      text-align: left;
    }
    .message .sent-at {
      display: block;
      font-size: 0.8em;
      color: #888;
      margin-top: 5px;
    }
    .chat-input {
      margin-top: 15px;
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
          <li class="breadcrumb-item active">View Messages</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section messages">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Inbox</h5>

              <!-- Message List -->
              <div id="message-list">
                <?php
                // Fetch messages from the database
                $sql = "SELECT m.id, m.message, m.sent_at, u.name AS receiver_name, m.sender_id, m.receiver_id
                        FROM messages m
                        JOIN users u ON m.receiver_id = u.user_id";
                $result = $db->query($sql);

                // Display messages
                while ($row = $result->fetch_assoc()) {
                  $message_class = ($row['sender_id'] == 1) ? 'sent' : 'received'; // Assuming sender_id = 1 is the logged-in user
                  echo '<div class="message ' . $message_class . '">';
                  echo '<h6 class="receiver-name">' . $row['receiver_name'] . '</h6>';
                  echo '<p class="message-text">' . $row['message'] . '</p>';
                  echo '<span class="sent-at">' . $row['sent_at'] . '</span>';
                  echo '</div>';
                }
                ?>
              </div>

              <!-- Chat Input -->
              <div class="chat-input">
                <form method="post" action="send_message.php">
                  <div class="input-group">
                    <textarea class="form-control" name="message" rows="3" placeholder="Type your message here..." required></textarea>
                    <input type="hidden" name="sender_id" value="1"> <!-- Replace with actual sender ID -->
                    <input type="hidden" name="receiver_id" value="2"> <!-- Replace with actual receiver ID -->
                    <button class="btn btn-primary" type="submit">Send</button>
                  </div>
                </form>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>
  </main><!-- End Main Content -->

 <?php 
  include('footer.php');
  ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/js/main.js"></script>

</body>

</html>
