<?php
include('zon.php');
$conn = new Con();
$db = $conn->connect();

$id = $_GET['id'];

$sql = "SELECT * FROM reminders WHERE reminder_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$reminder = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Edit Reminder</title>
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
      <h1>Edit Reminder</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item"><a href="reminders.php">Reminders</a></li>
          <li class="breadcrumb-item active">Edit Reminder</li>
        </ol>
      </nav>
    </div>

    <!-- ======= Edit Reminder Form ======= -->
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <form action="update-reminder.php" method="POST">
            <input type="hidden" name="reminder_id" value="<?php echo $reminder['reminder_id']; ?>">
            <div class="mb-3">
              <label for="appointment_id" class="form-label">Appointment ID</label>
              <input type="number" class="form-control" id="appointment_id" name="appointment_id" value="<?php echo $reminder['appointment_id']; ?>" required>
            </div>
            <div class="mb-3">
              <label for="reminder_date" class="form-label">Reminder Date</label>
              <input type="datetime-local" class="form-control" id="reminder_date" name="reminder_date" value="<?php echo date('Y-m-d\TH:i', strtotime($reminder['reminder_date'])); ?>" required>
            </div>
            <div class="mb-3">
              <label for="message" class="form-label">Message</label>
              <textarea class="form-control" id="message" name="message" rows="3" required><?php echo htmlspecialchars($reminder['message']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Reminder</button>
          </form>
        </div>
      </div>
    </section>

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

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
