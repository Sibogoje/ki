<?php
session_start();
include 'zon.php';
$conn = new Con();
$db = $conn->connect();

// Enable detailed error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$tables = ['users', 'booking_info'];
$columns = [
    'users' => ['user_id', 'surname', 'name', 'email', 'phone_number', 'user_role', 'created_at', 'town', 'region', 'age', 'marital', 'gender', 'education', 'orphan', 'disability', 'constituency', 'community', 'status'],
    'booking_info' => ['booking_id', 'user_id', 'user_surname', 'user_name', 'user_email', 'user_phone_number', 'counselor_id', 'counselor_name', 'counselor_surname', 'booking_date', 'status', 'cancellation_reason', 'last_modified_at', 'approved_by_counselor', 'mode']
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selectedColumns = isset($_POST['columns']) ? $_POST['columns'] : [];
    $selectedTables = isset($_POST['tables']) ? $_POST['tables'] : [];
    $joinCondition = isset($_POST['join_condition']) ? $_POST['join_condition'] : '';
    $whereCondition = isset($_POST['where_condition']) ? $_POST['where_condition'] : '';

    if (empty($selectedColumns) || empty($selectedTables)) {
        $_SESSION['error'] = 'Please select at least one column and one table.';
    } else {
        $query = "SELECT " . implode(', ', $selectedColumns) . " FROM " . implode(', ', $selectedTables);
        if (!empty($joinCondition)) {
            $query .= " ON " . $joinCondition;
        }
        if (!empty($whereCondition)) {
            $query .= " WHERE " . $whereCondition;
        }

        $result = $db->query($query);

        if ($result) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $_SESSION['data'] = $data;
            $_SESSION['columns'] = $selectedColumns;
        } else {
            $_SESSION['error'] = 'Invalid query or no results found.';
        }
    }
}

if (isset($_POST['export'])) {
    try {
        if (!isset($_SESSION['data']) || !isset($_SESSION['columns'])) {
            throw new Exception('No data available for export.');
        }

        $filename = "export_" . date("Y-m-d_H-i-s") . ".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=' . $filename);

        $output = fopen('php://output', 'w');
        if ($output === false) {
            throw new Exception('Failed to open output stream');
        }

        fputcsv($output, $_SESSION['columns']);

        foreach ($_SESSION['data'] as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit();
    } catch (Exception $e) {
        error_log('Error exporting data: ' . $e->getMessage());
        $_SESSION['error'] = 'Failed to export data. Please try again later.';
        header('Location: export-data.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Export Data</title>
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

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Data Management</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Data Export</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Custom Query Export</h5>
              <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                  <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
              <?php endif; ?>
              <form method="POST" action="export-data.php">
                <div class="mb-3">
                  <label for="tables" class="form-label">Select Tables</label>
                  <select class="form-select" id="tables" name="tables[]" multiple>
                    <?php foreach ($tables as $table): ?>
                      <option value="<?php echo $table; ?>"><?php echo $table; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="columns" class="form-label">Select Columns</label>
                  <select class="form-select" id="columns" name="columns[]" multiple>
                    <?php foreach ($columns as $table => $cols): ?>
                      <optgroup label="<?php echo $table; ?>">
                        <?php foreach ($cols as $col): ?>
                          <option value="<?php echo $table . '.' . $col; ?>"><?php echo $col; ?></option>
                        <?php endforeach; ?>
                      </optgroup>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="join_condition" class="form-label">Join Condition (Optional)</label>
                  <input type="text" class="form-control" id="join_condition" name="join_condition" placeholder="e.g., users.user_id = booking_info.user_id">
                </div>
                <div class="mb-3">
                  <label for="where_condition" class="form-label">Where Condition (Optional)</label>
                  <input type="text" class="form-control" id="where_condition" name="where_condition" placeholder="e.g., users.region = 'Hhohho'">
                </div>
                <button type="submit" class="btn btn-primary">Run Query</button>
              </form>
              <?php if (isset($_SESSION['data'])): ?>
                <div class="mt-4">
                  <h5>Query Results</h5>
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <?php foreach ($_SESSION['columns'] as $column): ?>
                            <th><?php echo htmlspecialchars($column); ?></th>
                          <?php endforeach; ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($_SESSION['data'] as $row): ?>
                          <tr>
                            <?php foreach ($row as $cell): ?>
                              <td><?php echo htmlspecialchars($cell); ?></td>
                            <?php endforeach; ?>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                  <form method="POST" action="export-data.php">
                    <button type="submit" name="export" class="btn btn-success">Export to Excel</button>
                  </form>
                </div>
              <?php endif; ?>
              <div class="mt-4">
                <h5>Hints for Writing SQL Queries</h5>
                <p>If you are not familiar with SQL, here are some example queries you can use:</p>
                <ul>
                  <li><strong>Get all users:</strong> <code>SELECT * FROM users;</code></li>
                  <li><strong>Get all bookings:</strong> <code>SELECT * FROM booking_info;</code></li>
                  <li><strong>Get users by region:</strong> <code>SELECT * FROM users WHERE region = 'Hhohho';</code></li>
                  <li><strong>Get bookings by status:</strong> <code>SELECT * FROM booking_info WHERE status = 'confirmed';</code></li>
                </ul>
                <h5>Available Tables and Fields</h5>
                <p><strong>users</strong> table fields:</p>
                <ul>
                  <li>user_id</li>
                  <li>surname</li>
                  <li>name</li>
                  <li>email</li>
                  <li>phone_number</li>
                  <li>user_role</li>
                  <li>created_at</li>
                  <li>password_hash</li>
                  <li>client_username</li>
                  <li>town</li>
                  <li>region</li>
                  <li>age</li>
                  <li>marital</li>
                  <li>gender</li>
                  <li>education</li>
                  <li>orphan</li>
                  <li>disability</li>
                  <li>constituency</li>
                  <li>community</li>
                  <li>status</li>
                </ul>
                <p><strong>booking_info</strong> table fields:</p>
                <ul>
                  <li>booking_id</li>
                  <li>user_id</li>
                  <li>user_surname</li>
                  <li>user_name</li>
                  <li>user_email</li>
                  <li>user_phone_number</li>
                  <li>counselor_id</li>
                  <li>counselor_name</li>
                  <li>counselor_surname</li>
                  <li>booking_date</li>
                  <li>status</li>
                  <li>cancellation_reason</li>
                  <li>last_modified_at</li>
                  <li>approved_by_counselor</li>
                  <li>mode</li>
                </ul>
              </div>
            </div>
          </div>
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
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
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