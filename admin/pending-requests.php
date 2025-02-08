<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Pending Requests</title>
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
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <?php include 'header.php'; ?>

  <!-- ======= Main ======= -->
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Pending Requests</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Pending Requests</li>
        </ol>
      </nav>
    </div>

    <!-- ======= Pending Requests Table ======= -->
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
          <table class="table table-hover datatable">
            <thead>
              <tr>
                <th>Request ID</th>
                <th>Client Name</th>
                <th>Counselor Name</th>
                <th>Appointment Date</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              include('con.php');
              $conn = new Con();
              $db = $conn->connect();

              $sql = "SELECT a.appointment_id, u.name AS client_name, u.surname AS client_surnam, c.full_name AS counselor_name, a.appointment_date, a.status
                      FROM appointments a
                      JOIN users u ON a.user_id = u.user_id
                      JOIN counselors c ON a.counselor_id = c.id
                      WHERE a.status = 'pending'";

              $result = $db->query($sql);

              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $client  = $row['client_name']." ".$row['client_surname'];
                  echo "<tr>
                          <td>{$row['appointment_id']}</td>
                          <td>{$client}</td>
                          <td>{$row['counselor_name']}</td>
                          <td>{$row['appointment_date']}</td>
                          <td>{$row['status']}</td>
                          <td>
                            <a href='scripts/approve-request.php?id={$row['appointment_id']}' class='btn btn-success btn-sm'>Approve</a>
                            <a href='scripts/reject-request.php?id={$row['appointment_id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to reject this request?');\">Reject</a>
                          </td>
                        </tr>";
                }
              } else {
                echo "<tr><td colspan='6'>No pending requests found.</td></tr>";
              }

              $db->close();
              ?>
            </tbody>
          </table>
         
          </div>
        </div>
      </div>
    </section>

  </main><!-- End #main -->

 <?php 
  include('footer.php');
  ?>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
