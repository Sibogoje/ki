<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Crisis Support</title>
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
            <h1>Client Management</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Client Management</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

  <!-- ======= Crisis Support Section ======= -->
  <section id="crisis-support" class="section">
    <div class="container">
      <div class="section-title">
        <h2>Crisis Support</h2>
        <p>Resources and contacts for immediate support in crisis situations.</p>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="table-responsive">
            <table class="table table-hover datatable">
              <thead>
                <tr>
                  <th>Resource ID</th>
                  <th>Title</th>
                  <th>Description</th>
                  <th>Contact</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                include('con.php');
                $conn = new Con();
                $db = $conn->connect();

                $sql = "SELECT * FROM crisis_support_resources";
                $result = $db->query($sql);

                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['resource_id']}</td>
                            <td>{$row['title']}</td>
                            <td>{$row['description']}</td>
                            <td>{$row['contact']}</td>
                            <td>
                              <a href='edit-resource.php?id={$row['resource_id']}' class='btn btn-warning btn-sm'>Edit</a>
                              <a href='scripts/delete-resource.php?id={$row['resource_id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this resource?');\">Delete</a>
                            </td>
                          </tr>";
                  }
                } else {
                  echo "<tr><td colspan='5'>No crisis support resources found.</td></tr>";
                }

                $db->close();
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section><!-- End Crisis Support Section -->

</div>
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
