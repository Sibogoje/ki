<?php
// Include the database connection file
include 'admin/zon.php';
$conn = new Con();
$db = $conn->connect();

// Get the news update ID from the query string
$news_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the news update details from the database
$query = "SELECT title, full_content, image_url, created_at FROM news_updates WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('i', $news_id);
$stmt->execute();
$result = $stmt->get_result();
$news = $result->fetch_assoc();
$stmt->close();
$db->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>News Detail</title>
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
            <span class="sr-only">Loading News...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <?php 
    include ('header.php');
    ?>

    <!-- Content Start -->
    <div class="content">
        <?php
        include('navbar.php');
        ?>

        <!-- News Detail Start -->
        <div class="container-fluid pt-4 px-4">
            <div class="row bg-light rounded align-items-center justify-content-between mx-0" style="padding: 20px;">
                <div class="col-md-12 mb-4">
                    <?php if ($news): ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <?php if ($news['image_url']): ?>
                                    <img src="<?php echo htmlspecialchars($news['image_url']); ?>" class="card-img-top" alt="News Image">
                                <?php endif; ?>
                                <h5 class="card-title"><?php echo htmlspecialchars($news['title']); ?></h5>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($news['full_content'])); ?></p>
                                <p class="card-text text-muted"><?php echo date('F j, Y, g:i a', strtotime($news['created_at'])); ?></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <p>No news update found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- News Detail End -->

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
