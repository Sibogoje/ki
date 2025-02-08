<?php
include 'admin/con.php';
$conn = new Con();
$db = $conn->connect();
session_start();

$userID = $_SESSION['counselor_id'];
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

        <?php include 'header.php'; ?>

        <!-- Content Start -->
        <div class="content">
            <?php include 'navbar.php'; ?>

            <div class="container-fluid pt-4 px-4">
                <div class="row bg-light rounded align-items-center justify-content-center mx-0" style="padding: 30px;">
                    <div class="col-md-12">
                        <h3>Conversation</h3>
                        <div class="chat-box mb-4" id="chat-box" style="height: 300px; overflow-y: scroll;">
                            <?php
                            while ($row = $result->fetch_assoc()) {
                                echo "<div class='message'>";
                                echo "<strong>" . $row['sender_name'] . ":</strong> " . nl2br(htmlspecialchars($row['message_text'])) . "<br>";
                                echo "<span class='text-muted'>" . date('F j, Y, g:i a', strtotime($row['sent_at'])) . "</span>";
                                echo "</div>";
                            }
                            ?>
                        </div>

                        <form action="send-message.php" method="post" id="message-form">
                            <input type="hidden" name="chat_id" value="<?php echo $chatID; ?>">
                            <div class="input-group">
                                <input type="text" name="message_text" class="form-control" placeholder="Type your message..." required>
                                <button class="btn btn-primary" type="submit">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php include 'footer.php'; ?>
        </div>
        <!-- Content End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
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

    <script>
        function fetchMessages() {
            $.ajax({
                url: 'fetch-messages.php',
                method: 'GET',
                data: { chat_id: <?php echo $chatID; ?> },
                success: function(response) {
                    $('#chat-box').html(response);
                    var chatBox = document.getElementById('chat-box');
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            });
        }

        $(document).ready(function() {
            // Fetch messages every 5 seconds
            setInterval(fetchMessages, 500);
            
            // Handle form submission
            $('#message-form').submit(function(event) {
                event.preventDefault(); // Prevent default form submission

                $.ajax({
                    url: 'send-message.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        fetchMessages(); // Fetch the latest messages
                        $('#message-form')[0].reset(); // Clear the form
                    }
                });
            });
        });
    </script>
</body>
</html>
