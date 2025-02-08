<?php

include 'con.php';
session_start();
// Database connection
$conn = new Con();
$db = $conn->connect();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>News Resource</title>
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

</head>

<body>

  <!-- ======= Header ======= -->
  <?php include 'header.php'; ?>
  
  
<main id="main" class="main">
        <div class="pagetitle">
            <h1>Manage Content</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Content</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="container">
                <div class="row">
                    <!-- Form Section -->
                    <div class="col-md-8">
                        <form id="contentForm" action="content_crud.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="contentId">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="short_description" class="form-label">Short Description</label>
                                <textarea class="form-control" id="short_description" name="short_description" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="full_content" class="form-label">Full Content</label>
                                <textarea class="form-control" id="full_content" name="full_content" rows="6" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Upload Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>
                            <button type="submit" name="action" value="create" class="btn btn-success">Save Content</button>
                            <button type="reset" class="btn btn-secondary">Clear</button>
                        </form>
                    </div>

                    <!-- List Section -->
                    <div class="col-md-4">
                        <h5>Existing Content</h5>
                        <div class="content-list border p-3">
                            <?php
                           
                            $query = "SELECT * FROM news_updates";
                            $result = $db->query($query);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<div class='d-flex justify-content-between align-items-center mb-2'>
                                            <span class='text-truncate' title='{$row['title']}'>{$row['title']}</span>
                                            <div>
                                                <button class='btn btn-sm btn-primary' onclick='editContent(" . json_encode($row) . ")'>Edit</button>
                                                <a href='content_crud.php?action=delete&id={$row['id']}' class='btn btn-sm btn-danger'>Delete</a>
                                            </div>
                                        </div>";
                                }
                            } else {
                                echo "<p class='text-muted'>No content available.</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

  <!-- ======= Footer ======= -->
 <?php 
  include('footer.php');
  ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  
  <script>
        function editContent(content) {
            document.getElementById('contentId').value = content.id;
            document.getElementById('title').value = content.title;
            document.getElementById('short_description').value = content.short_description;
            document.getElementById('full_content').value = content.full_content;
        }
    </script>

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
