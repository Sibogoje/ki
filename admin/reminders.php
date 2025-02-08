<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Reminders</title>
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
      <h1>Reminders</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Reminders</li>
        </ol>
      </nav>
    </div>

    <!-- ======= Reminders Table ======= -->
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addReminderModal">
            <i class="bx bx-plus"></i> Add Reminder
          </button>
          <div class="table-responsive">
          <table class="table table-hover datatable ">
            <thead>
              <tr>
                <th>Reminder ID</th>
                <th>Appointment ID</th>
                <th>Reminder Date</th>
                <th>Message</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              include('zon.php');
              $conn = new Con();
              $db = $conn->connect();

              $sql = "SELECT * FROM reminders";
              $result = $db->query($sql);

              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>
                          <td>{$row['reminder_id']}</td>
                          <td>{$row['appointment_id']}</td>
                          <td>{$row['reminder_date']}</td>
                          <td>{$row['message']}</td>
                          <td>
                            <a href='edit-reminder.php?id={$row['reminder_id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='scripts/delete-reminder.php?id={$row['reminder_id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this reminder?');\">Delete</a>
                          </td>
                        </tr>";
                }
              } else {
                echo "<tr><td colspan='5'>No reminders found.</td></tr>";
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


 <!-- Add Reminder Modal -->
  <div class="modal fade" id="addReminderModal" tabindex="-1" aria-labelledby="addReminderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addReminderModalLabel">Add Reminder</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="scripts/add-reminder.php" method="POST">
            <div class="mb-3">
              <label for="appointment_id" class="form-label">Appointment ID</label>
              <select class="form-control" id="appointment_id" name="appointment_id" required>
                <?php
               // include('zon.php');
               // $conn = new Con();
                $db = $conn->connect();

                $sql = "SELECT appointment_id
                        FROM appointments 
                        WHERE status IN ('confirmed', 'pending')";

                $result = $db->query($sql);

                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['appointment_id']}'>{$row['appointment_id']}</option>";
                  }
                } else {
                  echo "<option value=''>No available appointments</option>";
                }

                $db->close();
                ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="reminder_date" class="form-label">Reminder Date</label>
              <input type="datetime-local" class="form-control" id="reminder_date" name="reminder_date" required>
            </div>
            <div class="mb-3">
              <label for="message" class="form-label">Message</label>
              <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Reminder</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
