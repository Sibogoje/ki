<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Session Notes</title>
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

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <?php include 'header.php'; ?>

  <!-- ======= Main Content ======= -->
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Session Notes</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Session Notes</li>
        </ol>
      </nav>
    </div>

    <!-- Add Reminder Button -->
    <div class="container mt-3">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReminderModal">
        Add Notes
      </button>
    </div>

    <!-- Session Notes Table -->
    <div class="container mt-3">
      <div class="table-responsive">
        <table class="table table-hover datatable">
          <thead>
            <tr>
              <th>Note ID</th>
              <th>Appointment ID</th>
              <th>Note</th>
              <th>Created At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            include('zon.php');
            $conn = new Con();
            $db = $conn->connect();

            $sql = "SELECT * FROM session_notes";
            $result = $db->query($sql);

            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['appointment_id']}</td>
                        <td>{$row['note']}</td>
                        <td>{$row['created_at']}</td>
                        <td>
                           
                          <a href='scripts/delete-session-note.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this note?');\">Delete</a>
                        </td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='5'>No notes found.</td></tr>";
            }

            //$db->close();
            ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Add Reminder Modal -->
    <div class="modal fade" id="addReminderModal" tabindex="-1" aria-labelledby="addReminderModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addReminderModalLabel">Add Session Note</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="scripts/add-session-note.php" method="POST">
             
              <div class="mb-3">
              <label for="appointment_id" class="form-label">Select Appointment</label>
              <select class="form-control" id="appointment_id" name="appointment_id" required>
                <option value="">Select an appointment</option>
                <?php
               
                // Fetch appointments with counselor and client details
                $sql = "SELECT 
                          a.appointment_id, 
                          c.full_name AS counselor_name, 
                          u.name AS client_name, 
                          u.surname AS client_surname,
                          a.appointment_date
                        FROM appointments a
                        JOIN counselors c ON a.counselor_id = c.id
                        JOIN users u ON a.user_id = u.user_id";
                $result = $db->query($sql);
            
                // Populate the select options with appointment data
                while ($row = $result->fetch_assoc()) {
                    $client = $row['client_name']."  ".$row['client_surname'];
                  echo '<option value="' . $row['appointment_id'] . '">' . $row['counselor_name'] . ' - ' . $client . ' (' . $row['appointment_date'] . ')</option>';
                }
                ?>
              </select>
            </div>

              <div class="mb-3">
                <label for="note" class="form-label">Note</label>
                <textarea class="form-control" id="note" name="note" rows="3" required></textarea>
              </div>
              <button type="submit" class="btn btn-primary">Add Note</button>
            </form>
          </div>
        </div>
      </div>
    </div>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </footer><!-- End Footer -->

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
