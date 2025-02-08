<?php
include 'session.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Counselors Management</title>

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
  <?php include 'header.php' ?>

  <main id="main" class="main">
    <div class="pagetitle">
        <h1>Counselor Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Counselor Management</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Counselors</h5>
                        <div class="table-responsive">
                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Full Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone Number</th>
                                    <th scope="col">Credentials</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Include database connection
                                include('zon.php');
                                $conn = new Con();
                                $db = $conn->connect();

                                // Fetch all counselors
                                $query = "SELECT * FROM counselors";
                                $result = mysqli_query($db, $query);

                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<th scope='row'>" . $row['id'] . "</th>";
                                    echo "<td>" . $row['username'] . "</td>";
                                    echo "<td>" . $row['full_name'] . "</td>";
                                    echo "<td>" . $row['email'] . "</td>";
                                    echo "<td>" . $row['phone_number'] . "</td>";
                                    echo "<td>" . $row['credentials'] . "</td>";
                                    echo "<td>
                                            <a href='#' class='btn btn-success btn-sm' data-bs-toggle='modal' data-bs-target='#editCounselorModal' data-id='" . $row['id'] . "' data-username='" . $row['username'] . "' data-full_name='" . $row['full_name'] . "' data-email='" . $row['email'] . "' data-phone_number='" . $row['phone_number'] . "' data-credentials='" . $row['credentials'] . "'><i class='bi bi-pencil'></i></a>
                                            <a href='scripts/delete-counselor.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this counselor?\")'><i class='bi bi-trash'></i></a>
                                          </td>";
                                    echo "</tr>";
                                }
                                mysqli_close($db);
                                ?>
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Floating Action Button -->
    <button type="button" class="fab" data-bs-toggle="modal" data-bs-target="#addCounselorModal">
        <i class="bi bi-plus-lg"></i>
    </button>

    <!-- Modal for Adding New Counselor -->
    <div class="modal fade" id="addCounselorModal" tabindex="-1" aria-labelledby="addCounselorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCounselorModalLabel">Add New Counselor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="scripts/add-counselor.php" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="credentials" class="form-label">Credentials</label>
                            <input type="text" class="form-control" id="credentials" name="credentials" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Counselor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Counselor -->
    <div class="modal fade" id="editCounselorModal" tabindex="-1" aria-labelledby="editCounselorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCounselorModalLabel">Edit Counselor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="scripts/edit-counselor.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="edit_username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="edit_full_name" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="mb-3">
                       
                            <label for="edit_phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="edit_phone_number" name="phone_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_credentials" class="form-label">Credentials</label>
                            <input type="text" class="form-control" id="edit_credentials" name="credentials" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Confirming Counselor Deletion -->
    <div class="modal fade" id="deleteCounselorModal" tabindex="-1" aria-labelledby="deleteCounselorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCounselorModalLabel">Delete Counselor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="scripts/delete-counselor.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="delete_id" name="id">
                        <p>Are you sure you want to delete this counselor?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

  </main><!-- End #main -->
<?php 
  include('footer.php');
  ?>
  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

  <script>
    // Script to populate edit modal with data
    const editCounselorModal = document.getElementById('editCounselorModal');
    editCounselorModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const username = button.getAttribute('data-username');
        const fullName = button.getAttribute('data-full_name');
        const email = button.getAttribute('data-email');
        const phoneNumber = button.getAttribute('data-phone_number');
        const credentials = button.getAttribute('data-credentials');

        const modal = editCounselorModal.querySelector('form');
        modal.querySelector('#edit_id').value = id;
        modal.querySelector('#edit_username').value = username;
        modal.querySelector('#edit_full_name').value = fullName;
        modal.querySelector('#edit_email').value = email;
        modal.querySelector('#edit_phone_number').value = phoneNumber;
        modal.querySelector('#edit_credentials').value = credentials;
    });

    // Script to populate delete modal with data
    const deleteCounselorModal = document.getElementById('deleteCounselorModal');
    deleteCounselorModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');

        const modal = deleteCounselorModal.querySelector('form');
        modal.querySelector('#delete_id').value = id;
    });
  </script>

</body>

</html>
