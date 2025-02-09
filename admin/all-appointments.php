<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>All Appointments</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

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
  
    <style>
    /* Styles for the Floating Action Button (FAB) */
    .fab {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background-color: #007bff;
        color: white;
        border-radius: 50%;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3);
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 24px;
        cursor: pointer;
        z-index: 1000;
        border: none;
        outline: none;
    }
  </style>
</head>

<body>

  <!-- ======= Header ======= -->
  <?php include 'header.php'; ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>All Appointments</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">All Appointments</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Appointments Table</h5>
<div class="table-responsive">
              <!-- Table with hoverable rows -->
              <table class="table table-hover datatable table-responsive">
  <thead>
    <tr>
     
      <th>Client Name</th>
      <th>Counselor Name</th>
      <th>Appointment Date</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php
    include('zon.php');
    $conn = new Con();
    $db = $conn->connect();

    // Join appointments with clients and counselors to get their names
    $sql = "SELECT 
    a.booking_id, 
    client.name AS client_name, 
    client.surname AS client_surname, 
    counselor.name AS counselor_name,
    counselor.surname AS counselor_surname,
    a.booking_date, 
    a.status, 
    client.user_id AS client_user_id, 
    counselor.user_id AS counselor_user_id
FROM bookings a
LEFT JOIN users client ON a.user_id = client.user_id
LEFT JOIN users counselor ON a.counselor_id = counselor.user_id;
";

    $result = $db->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          
          $client = $row['client_name']."   ".$row['client_surname'];
          $user_id = $row['user_id'];
          $counselor = $row['counselor_id'];
          $appointment_id = $row['booking_id'];
          $app_date = $row['booking_date'];
        echo "<tr data-bs-toggle= 'collapse' data-bs-target=".$client.">
                
                <td>{$client}</td>
                <td>{$row['counselor_name']}    {$row['counselor_surname']}</td>
                <td>{$row['booking_date']}</td>
                <td>{$row['status']}</td>
                <td>
                  <a href='#' class='btn btn-edit btn-info btn-sm' 
                   data-appointmentid='{$row['booking_id']}'
                   data-userid='{$client}' 
                   data-counselor='{$row['counselor_name']}'
                   data-status='{$row['status']}'
                   data-appointmentdate='{$row['booking_date']}'>
                   View
                    </a>
    
                  <a href='scripts/delete-appointment.php?id={$row['booking_id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this appointment?');\">Delete</a>
                </td>
              </tr>";
      }
    } else {
      echo "<tr><td colspan='6'>No appointments found.</td></tr>";
    }

    $db->close();
    ?>
  </tbody>
</table>
</div>

              <!-- End Table with hoverable rows -->
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Floating Action Button -->
<a href="#" class="btn btn-primary btn-floating fab" data-bs-toggle="modal" data-bs-target="#bookAppointmentModal">
  <i class="bi bi-calendar-plus"></i>
</a>

<!-- Booking Appointment Modal -->
<div class="modal fade" id="bookAppointmentModal" tabindex="-1" aria-labelledby="bookAppointmentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bookAppointmentModalLabel">Book Appointment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="bookAppointmentForm">
          <div class="mb-3">
            <label for="clientSelect" class="form-label">Select Client</label>
            <select class="form-select" id="clientSelect" name="clientSelect" required>
              <!-- Options will be populated dynamically -->
            </select>
          </div>
          <div class="mb-3">
            <label for="appointmentDate" class="form-label">Appointment Date and Time</label>
            <input type="datetime-local" class="form-control" id="appointmentDate" name="appointmentDate" required>
          </div>
          <div class="mb-3">
            <label for="counselorSelect" class="form-label">Available Counselors</label>
            <select class="form-select" id="counselorSelect" name="counselorSelect" required>
              <!-- Options will be populated dynamically -->
            </select>
          </div>
          <div class="mb-3">
            <label for="mode" class="form-label">Select Mode</label>
            <select class="form-select" id="mode" name="mode" required>
             <option value="Online">Online</option>
              <option value="Offline">In Person</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Book Appointment</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Appointment Modal -->
<div class="modal fade" id="editAppointmentModal" tabindex="-1" aria-labelledby="editAppointmentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editAppointmentModalLabel">Edit Appointment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editAppointmentForm" Method="POST"  Action="scripts/edit-appointment.php">
          <input type="hidden" id="editAppointmentId" name="editAppointmentId">
          <div class="mb-3">
            <label for="editClientSelect" class="form-label">Client</label>
             <input type="text" class="form-control" id="editClientSelect" name="editClientSelect" required readonly>
          </div>
          <div class="mb-3">
            <label for="editCounselorSelect" class="form-label">Counselor</label>
             <input type="text" class="form-control" id="editCounselorSelect" name="editCounselorSelect" required readonly>
          </div>
          <div class="mb-3">
            <label for="editAppointmentDate" class="form-label">Appointment Date and Time</label>
            <input type="datetime-local" class="form-control" id="editAppointmentDate" name="editAppointmentDate" required>
          </div>
          <div class="mb-3">
            <label for="editStatusSelect" class="form-label">Status</label>
            <input type="datetime-local" class="form-control" id="editStatusSelect" name="editStatusSelect" disabled>
            
          </div>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
$(document).on('click', '.btn-edit', function() {
    const appointmentID = $(this).data('appointmentid');
    const userID = $(this).data('userid');
    const appointmentDate = $(this).data('appointmentdate');
    const counselorID = $(this).data('counselor');
    const status = $(this).data('status');

    $('#editAppointmentId').val(appointmentID);
    $('#editClientSelect').val(userID);
    const formattedDate = new Date(appointmentDate).toISOString().slice(0, 16);
    $('#editAppointmentDate').val(formattedDate);
    $('#editCounselorSelect').val(counselorID);
    $('#editStatusSelect').val(status);

    $('#editAppointmentModal').modal('show');
});


</script>


  </main><!-- End #main -->

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
  
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    // Populate client select options
    fetchClients();

    // Event listener for appointment date change
    document.getElementById('appointmentDate').addEventListener('change', function() {
      checkAvailableCounselors(this.value);
    });

    // Handle form submission
    document.getElementById('bookAppointmentForm').addEventListener('submit', function(event) {
      event.preventDefault();
      bookAppointment();
    });
  });

  function fetchClients() {
    fetch('scripts/get-clients.php')
      .then(response => response.json())
      .then(data => {
        const clientSelect = document.getElementById('clientSelect');
        clientSelect.innerHTML = '<option value="" disabled selected>Select Client</option>';
        data.forEach(client => {
          clientSelect.innerHTML += `<option value="${client.user_id}">${client.name}</option>`;
        });
      });
  }

  function checkAvailableCounselors(dateTime) {
    fetch('scripts/check-counselors.php?datetime=' + encodeURIComponent(dateTime))
      .then(response => response.json())
      .then(data => {
        const counselorSelect = document.getElementById('counselorSelect');
        counselorSelect.innerHTML = '<option value="" disabled selected>Select Counselor</option>';
        data.forEach(counselor => {
          counselorSelect.innerHTML += `<option value="${counselor.id}">${counselor.name}</option>`;
        });
      });
  }
</script>

<script>
function bookAppointment() {
    // Select the form element
    const form = document.getElementById('bookAppointmentForm');

    // Create FormData object to grab all form data automatically
    const formData = new FormData(form);

    // Send the data via AJAX
    $.ajax({
        url: 'scripts/book-appointment.php',
        type: 'POST',
        data: formData,
        processData: false, // Prevent jQuery from automatically transforming the data into a query string
        contentType: false, // Prevent jQuery from setting the content type header
        success: function(response) {
            alert(response);
            if (response === 'Appointment booked successfully.') {
                location.reload();
            } else {
                alert('Appointment booking not successful.');
            }
        },
        error: function() {
            alert('There was a problem booking the appointment.');
        }
    });

    return false; // Prevent default form submission
}
</script>






  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
