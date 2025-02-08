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

  <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Application</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<style>
        .chat-messages {
            height: 50vh;
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
        }
 .reply-box {
    position: relative;
    bottom: 0;
    width: 70%;
    background-color: #fff;
    padding: 10px;
    border-top: 1px solid #ccc;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
    margin: 0 auto; /* Centers the box within the parent container */
}


    </style>

</head>
<body>
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


        // Get the chat_id from the URL parameter
        $chat_id = $_GET['chat_id'];

        // Fetch messages for the selected chat
        $sql = "SELECT m.message_id, m.message_text, m.sent_at, u.name AS sender_name
                FROM messages m
                JOIN users u ON m.sender_id = u.user_id
                WHERE m.chat_id = '$chat_id'
                ORDER BY m.sent_at ASC";
        $result = $db->query($sql);
        ?>

        <section class="section">
            <div class="row">
               

                            <!-- Messages List -->
                            <ul class="list-group chat-messages">
                                <?php
                                // Display messages
                                while ($row = $result->fetch_assoc()) {
                                    echo '<li class="list-group-item">';
                                    echo '<strong>' . $row['sender_name'] . ':</strong> ' . $row['message_text'];
                                    echo '<br><small class="text-muted">' . $row['sent_at'] . '</small>';
                                    echo '</li>';
                                }
                                ?>
                            </ul>
                            <!-- End Messages List -->

                            <!-- Reply Box -->
                            <div class="reply-box">
                                <form id="replyForm" method="POST" action="">
                                    <input type="hidden" name="chat_id" value="<?php echo $chat_id; ?>">
                                    <div class="input-group">
                                        <input type="text" name="message_text" class="form-control" placeholder="Type your reply..." required>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i class="fas fa-paper-plane"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- End Reply Box -->
        
            </div>
        </section>
    </main><!-- End Main Content -->

    <!-- Include Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var chatMessages = document.querySelector(".chat-messages");
        chatMessages.scrollTop = chatMessages.scrollHeight;
    });
</script>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('#replyForm').on('submit', function(e) {
            e.preventDefault(); // Prevent form submission

            $.ajax({
                url: 'scripts/send-reply.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        // Append the new message to the chat list
                        $('.chat-messages').append(
                            '<li class="list-group-item"><strong>' + response.sender_name + ':</strong> ' +
                            response.message_text + '<br><small class="text-muted">' +
                            response.sent_at + '</small></li>'
                        );
                        // Clear the input field
                        $('input[name="message_text"]').val('');
                        // Scroll to the bottom of the chat
                        $('.chat-messages').scrollTop($('.chat-messages')[0].scrollHeight);
                    } else {
                        alert('Error sending reply. Please try again.');
                    }
                },
                error: function() {
                    alert('Error connecting to server.');
                }
            });
        });
    });
</script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
        var chatMessages = document.querySelector(".chat-messages");

        // Function to fetch messages and update the list
        function fetchMessages() {
            var chatId = "<?php echo $chat_id; ?>"; // PHP variable for the chat ID
            fetch("scripts/fetch_messages.php?chat_id=" + chatId)
                .then(response => response.json())
                .then(messages => {
                    chatMessages.innerHTML = ""; // Clear existing messages

                    // Append each message to the chat
                    messages.forEach(message => {
                        var messageItem = document.createElement("li");
                        messageItem.className = "list-group-item";
                        messageItem.innerHTML = `<strong>${message.sender_name}:</strong> ${message.message_text} <br><small class="text-muted">${message.sent_at}</small>`;
                        chatMessages.appendChild(messageItem);
                    });

                    // Scroll to the bottom
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                })
                .catch(error => console.error("Error fetching messages:", error));
        }

        // Initial load of messages
        fetchMessages();

        // Fetch new messages every 5 seconds
        setInterval(fetchMessages, 100);
    });
</script>


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
