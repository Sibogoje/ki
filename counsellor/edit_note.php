<?php
// Include the database connection file
include 'admin/zon.php';
$conn = new Con();
$db = $conn->connect();
// Start a session
session_start();

$noteID = isset($_GET['note_id']) ? intval($_GET['note_id']) : 0;
$userID = $_SESSION['counselor_id'];

// Fetch the note details
$query = "SELECT note_id, title, body FROM notes WHERE note_id = ? AND user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('ii', $noteID, $userID);
$stmt->execute();
$result = $stmt->get_result();
$note = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['note_title'];
    $body = $_POST['note_body'];

    // Update the note in the database
    $query = "UPDATE notes SET title = ?, body = ? WHERE note_id = ? AND user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ssii', $title, $body, $noteID, $userID);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Note updated successfully!';
        header('Location: notes.php');
        exit();
    } else {
        $_SESSION['error'] = 'Failed to update note.';
    }

    $stmt->close();
    $db->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Edit Note</title>
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

        <?php include('header.php'); ?>

        <!-- Content Start -->
        <div class="content">
            <?php include('navbar.php'); ?>

            <!-- Edit Note Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row bg-light rounded align-items-center justify-content-center mx-0">
                    <div class="col-md-12 mb-4" style="padding: 20px;">
                        <h3>Edit Note</h3>
                        <?php if ($note): ?>
                            <form action="edit_note.php?note_id=<?php echo $noteID; ?>" method="post">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="noteTitle" name="note_title" placeholder="Title" value="<?php echo htmlspecialchars($note['title']); ?>" required>
                                    <label for="noteTitle">Title</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="noteBody" name="note_body" placeholder="Note Body" rows="5" required><?php echo htmlspecialchars($note['body']); ?></textarea>
                                    <label for="noteBody">Note Body</label>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Note</button>
                            </form>
                        <?php else: ?>
                            <p>Note not found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- Edit Note End -->

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
