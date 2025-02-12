<?php
// Include the database connection file
include 'admin/zon.php';
$conn = new Con();
$db = $conn->connect();
// Start a session
session_start();

$userID = $_SESSION['counselor_id']; 
// Fetch user's notes 
$query = "SELECT note_id, title, body, created_at FROM notes WHERE user_id = ? ORDER BY created_at DESC"; 
$stmt = $db->prepare($query); 
$stmt->bind_param('i', $userID); 
$stmt->execute(); 
$result = $stmt->get_result(); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Notes</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

 <!-- Favicon -->
 <link href="../icon.png" rel="icon">

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

        <?php include('header.php'); ?>

        <!-- Content Start -->
        <div class="content">
            <?php include('navbar.php'); ?>

            <!-- Notes Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row bg-light rounded align-items-center justify-content-center mx-0">
                    <div class="col-md-12 mb-4" style="padding: 20px;">
                        <h3>My Notes</h3>
                        <form action="save_note.php" method="post">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="noteTitle" name="note_title" placeholder="Title" required>
                                <label for="noteTitle">Title</label>
                            </div>
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="noteBody" name="note_body" placeholder="Note Body" rows="5" required></textarea>
                                <label for="noteBody">Note Body</label>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Note</button>
                        </form>

                        <h3 class="mt-4">Previously Stored Notes</h3>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<div class='card mb-4'>";
                                echo "<div class='card-body'>";
                                echo "<h5 class='card-title'>" . htmlspecialchars($row['title']) . "</h5>";
                                echo "<p class='card-text'>" . nl2br(htmlspecialchars($row['body'])) . "</p>";
                                echo "<p class='card-text text-muted'>" . date('F j, Y, g:i a', strtotime($row['created_at'])) . "</p>";
                                echo "<a href='edit_note.php?note_id=" . $row['note_id'] . "' class='btn btn-info'>Edit</a> ";
                                echo "<a href='delete_note.php?note_id=" . $row['note_id'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this note?\");'>Delete</a>";
                                echo "</div></div>";
                            }
                        } else {
                            echo "<p>No notes available.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- Notes End -->

            <?php include('footer.php'); ?>
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
</body>

</html>