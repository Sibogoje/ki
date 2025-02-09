<?php
// Include database connection
include 'zon.php';
$conn = new Con();
$db = $conn->connect();
session_start();
// Query to count the total number of completed appointments
$sql = "SELECT COUNT(*) AS total_appointments FROM bookings"; // Adjust status if necessary
$result = $db->query($sql);
$row = $result->fetch_assoc();
$totalAppointments = $row['total_appointments'];

// Query to count the total number of active counselors (assuming 'role' column is used)
$sql = "SELECT COUNT(*) AS user_id FROM users WHERE user_role = 'counselor'"; // Adjust role or identifier if necessary
$result = $db->query($sql);
$row = $result->fetch_assoc();
$totalCounselors = $row['user_id'];

// Query to count the number of clients by region and gender
$sql = "SELECT region, gender, COUNT(*) AS client_count FROM users WHERE user_role = 'client' GROUP BY region, gender";
$result = $db->query($sql);

// Initialize data arrays
$regions = [];
$maleCounts = [];
$femaleCounts = [];

// Fetch data and store it in arrays
while ($row = $result->fetch_assoc()) {
    $region = $row['region'];
    $gender = $row['gender'];
    $client_count = $row['client_count'];

    if (!in_array($region, $regions)) {
        $regions[] = $region;
    }

    if ($gender == 'Male') {
        $maleCounts[$region] = $client_count;
    } elseif ($gender == 'Female') {
        $femaleCounts[$region] = $client_count;
    }
}

// Ensure all regions have data for both genders
foreach ($regions as $region) {
    if (!isset($maleCounts[$region])) {
        $maleCounts[$region] = 0;
    }
    if (!isset($femaleCounts[$region])) {
        $femaleCounts[$region] = 0;
    }
}

// Query to fetch the latest 5 activities
$sql = "SELECT al.timestamp, al.action_type, al.description, u.name 
        FROM activity_log al 
        JOIN users u ON al.user_id = u.user_id
        ORDER BY al.timestamp DESC LIMIT 5";
$result = $db->query($sql);

// Initialize array to store activity items
$activities = [];
while ($row = $result->fetch_assoc()) {
    $activities[] = $row;
}


// Redirect to login page if the user is not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Example content for the logged-in user
//echo "Welcome, " . htmlspecialchars($_SESSION['username']) . "!";
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Admin Dashboard</title>
  <meta content="Admin Dashboard for Mental Health App" name="description">
  <meta content="dashboard, admin, mental health" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600|Nunito:300,400,600|Poppins:300,400,600" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <?php include 'header.php'; ?>

  <!-- ======= Sidebar ======= -->
  <?php include 'sidebar.php'; ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Admin Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

<!-- Appointments Overview -->
<div class="col-lg-6">
  <div class="card info-card appointments-card">
    <div class="card-body">
      <h5 class="card-title">Appointments Overview</h5>

      <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
          <i class="bi bi-calendar-event"></i>
        </div>
        <div class="ps-3">
          <!-- Display total appointments dynamically -->
          <h6><?php echo $totalAppointments; ?></h6>
          <span class="text-success small pt-1 fw-bold">5%</span> <span class="text-muted small pt-2 ps-1">increase</span>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Appointments Overview -->


<!-- Active Counselors -->
<div class="col-lg-6">
  <div class="card info-card counselors-card">
    <div class="card-body">
      <h5 class="card-title">Active Counselors</h5>

      <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
          <i class="bi bi-person-check"></i>
        </div>
        <div class="ps-3">
          <!-- Display total counselors dynamically -->
          <h6><?php echo $totalCounselors; ?></h6>
          <span class="text-success small pt-1 fw-bold">2%</span> <span class="text-muted small pt-2 ps-1">increase</span>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Active Counselors -->


<!-- User Data -->
<div class="col-lg-12">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Client Registration</h5>
      <div id="feedbackChart" style="min-height: 400px;" class="echart"></div>

      <script>
        document.addEventListener("DOMContentLoaded", () => {
          // Get data from PHP to display dynamically
          var regions = <?php echo json_encode($regions); ?>;
          var maleCounts = <?php echo json_encode(array_values($maleCounts)); ?>;
          var femaleCounts = <?php echo json_encode(array_values($femaleCounts)); ?>;

          echarts.init(document.querySelector("#feedbackChart")).setOption({
            tooltip: {
              trigger: 'axis'
            },
            legend: {
              data: ['Male', 'Female']
            },
            xAxis: {
              type: 'category',
              data: regions // Use the regions as categories on the x-axis
            },
            yAxis: {
              type: 'value'
            },
            series: [
              {
                name: 'Male',
                type: 'bar',
                data: maleCounts // Use the male client counts for the bar data
              },
              {
                name: 'Female',
                type: 'bar',
                data: femaleCounts // Use the female client counts for the bar data
              }
            ]
          });
        });
      </script>
    </div>
  </div>
</div><!-- End User Data -->


<!-- Recent Activity -->
<div class="col-lg-12">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Recent Activity</h5>
      <div class="activity">
        <?php
        foreach ($activities as $activity) {
            // Calculate the time difference
            $activity_time = strtotime($activity['timestamp']);
            $time_diff = time() - $activity_time;
            $minutes = floor($time_diff / 60); // Minutes
            $hours = floor($time_diff / 3600); // Hours

            // Determine activity label color based on action type
            $badge_class = '';
            if ($activity['action_type'] == 'New Booking') {
                $badge_class = 'text-success';
            } elseif ($activity['action_type'] == 'New Registration') {
                $badge_class = 'text-primary';
            } else {
                $badge_class = 'text-warning';
            }
        ?>
        <div class="activity-item d-flex">
          <div class="activite-label"><?php echo $hours . ' hr'; ?></div>
          <i class='bi bi-circle-fill activity-badge <?php echo $badge_class; ?> align-self-start'></i>
          <div class="activity-content">
            <a href="#" class="fw-bold text-dark"><?php echo $activity['action_type']; ?></a> by <?php echo $activity['name']; ?>
          </div>
        </div><!-- End activity item-->
        <?php } ?>
      </div>
    </div>
  </div><!-- End Recent Activity -->
</div><!-- End Right side columns -->


      </div>
    </section>

  </main><!-- End #main -->

  <?php 
  include('footer.php');
  ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
