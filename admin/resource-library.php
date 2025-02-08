<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Resource Library</title>
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

  <!-- ======= Main Content ======= -->
  <main id="main" class="main">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h2>Resource Library</h2>

          <!-- Add Resource Button -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResourceModal">Add Resource</button>

          <!-- Resource Table -->
          <div class="table-responsive">
            <table class="table table-hover datatable">
              <thead>
                <tr>
                 
                  <th>Title</th>
                  <th>Description</th>
                  <th>File</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                include('zon.php');
                $conn = new Con();
                $db = $conn->connect();

                $sql = "SELECT * FROM resources";
                $result = $db->query($sql);

                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                           
                            <td>{$row['title']}</td>
                            <td>{$row['description']}</td>
                            <td><a href='{$row['file']}' target='_blank'>View</a></td>
                            <td>
                             
                              <a href='scripts/delete-resource.php?id={$row['resource_id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this resource?');\">Delete</a>
                            </td>
                          </tr>";
                  }
                } else {
                  echo "<tr><td colspan='5'>No resources found.</td></tr>";
                }

                $db->close();
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </main><!-- End #main -->

 <?php 
  include('footer.php');
  ?>

  <!-- Add Resource Modal -->
  <div class="modal fade" id="addResourceModal" tabindex="-1" aria-labelledby="addResourceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addResourceModalLabel">Add Resource</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="scripts/add-resource.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="title" class="form-label">Title</label>
              <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
              <label for="file" class="form-label">File</label>
              <input type="file" class="form-control" id="file" name="file" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Resource</button>
          </form>
        </div>
      </div>
    </div>
  </div><!-- End Add Resource Modal -->

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
