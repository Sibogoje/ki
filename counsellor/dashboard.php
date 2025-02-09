<?php // Include the database connection file
include 'admin/zon.php';
$conn = new Con();
$db = $conn->connect();
// Start a session
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Home</title>
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

    <?php 
    include ('header.php');
    ?>

    <!-- Content Start -->
    <div class="content">
        <?php
        include('navbar.php');
        ?>

        <!-- Dashboard Start -->
        <div class="container-fluid pt-4 px-4" >
            <div class="row bg-light rounded align-items-center justify-content-between mx-0" style="padding: 20px;">
                
                <!-- Content from news_updates table -->
                <div class="col-md-12 mb-4">
                    <h3>Latest News & Updates</h3>
                    <?php
                    // Fetch content from news_updates table
                    $query = "SELECT id, title, short_description, full_content, image_url, created_at FROM news_updates ORDER BY created_at DESC LIMIT 5"; 
                    $result = $db->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='card mb-4'>";
                            echo "<div class='row g-0'>";
                            echo "<div class='col-md-8'>";
                            echo "<div class='card-body'>";
                            echo "<h5 class='card-title'>" . htmlspecialchars($row['title']) . "</h5>";
                            echo "<p class='card-text'>" . htmlspecialchars($row['short_description']) . "</p>";
                            echo "<p class='card-text text-muted'>" . date('F j, Y, g:i a', strtotime($row['created_at'])) . "</p>";
                            echo "<a href='news_detail.php?id=" . $row['id'] . "' class='btn btn-primary'>Read More</a>";
                            echo "</div></div>";
                            if ($row['image_url']) {
                                echo "<div class='col-md-4'>";
                                echo "<img src='../" . htmlspecialchars($row['image_url']) . "' class='img-fluid rounded-start' alt='News Image' style='height: 100px; width: 100px; object-fit: cover;'>";
                                echo "</div>";
                            }
                            echo "</div></div>";
                        }
                    } else {
                        echo "<p>No news updates available.</p>";
                    }

                    $db->close();
                    ?>
                </div>
            </div>
        </div>
        <!-- Dashboard End -->

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