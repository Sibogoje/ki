<?php 
include 'session.php';
include('con.php');
$conn = new Con();
$db = $conn->connect();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Meta and link tags -->
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Notifications</title>
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Option 1: Include in HTML -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
  <?php include 'header.php'; ?>

  <main id="main" class="main">
      <div class="pagetitle">
          <h1>Notifications</h1>
          <nav>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                  <li class="breadcrumb-item active">Notifications</li>
              </ol>
          </nav>
      </div>

      <section class="section notifications-section">
          <div class="row">
              <div class="col-lg-12">
                  <div class="card">
                      <div class="card-body">
                          <h5 class="card-title">Notifications</h5>

                          <!-- Add Notification Button -->
                          <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addNotificationModal">Add New Notification</button>

                          <!-- Notifications Table -->
                          <table class="table table-bordered">
                              <thead>
                                  <tr>
                                      <th>ID</th>
                                      <th>Title</th>
                                      <th>Message</th>
                                      <th>Created At</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php
                                  // Fetch notifications from the database
                                  $sql = "SELECT * FROM notifications ORDER BY created_at DESC";
                                  $result = $db->query($sql);

                                  if ($result->num_rows > 0) {
                                      while ($row = $result->fetch_assoc()) {
                                          echo "<tr>";
                                          echo "<td>{$row['id']}</td>";
                                          echo "<td>{$row['title']}</td>";
                                          echo "<td>{$row['message']}</td>";
                                          echo "<td>{$row['created_at']}</td>";
                                          echo "</tr>";
                                      }
                                  } else {
                                      echo "<tr><td colspan='4' class='text-center'>No notifications found</td></tr>";
                                  }
                                  ?>
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
          </div>
      </section>
  </main>

  <!-- Add Notification Modal -->
  <div class="modal fade" id="addNotificationModal" tabindex="-1" aria-labelledby="addNotificationModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="addNotificationModalLabel">Add New Notification</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <form id="addNotificationForm" method="POST" action="scripts/add_notification.php">
                      <div class="mb-3">
                          <label for="title" class="form-label">Title</label>
                          <input type="text" class="form-control" id="title" name="title" required>
                      </div>
                      <div class="mb-3">
                          <label for="message" class="form-label">Message</label>
                          <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                      </div>
                      <button type="submit" class="btn btn-primary">Save Notification</button>
                  </form>
              </div>
          </div>
      </div>
  </div>

  <?php include('footer.php'); ?>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>
