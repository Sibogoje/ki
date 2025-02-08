<?php
// Include the database connection file
include 'admin/con.php';
$conn = new Con();
$db = $conn->connect();
// Start a session
session_start();


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);




$userID = $_SESSION['counselor_id']; 
// Fetch user's notes 


$query = "SELECT note_id, title, body, created_at FROM notes WHERE user_id = ? Order by created_at DESC"; 
$stmt = $db->prepare($query); $stmt->bind_param('i', $userID); $stmt->execute(); 
$result = $stmt->get_result(); 




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Profile</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


      <?php 
      include ('header.php');
      ?>


        <!-- Content Start -->
        <div class="content">
            <?php
            include('navbar.php');
            ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

            <!-- Blank Start -->
<?php
// Fetch the user data for the profile
$query = "SELECT surname, name, email, phone_number, user_role, created_at, client_username, town, region 
          FROM users 
          WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('i', $userID);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();
?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center mx-0">
        <div class="col-md-12 mb-4" style="padding: 20px;">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3>Profile</h3>
                </div>
                <div class="card-body">
                    <!-- Basic Information -->
                    <h5 class="mb-3">Basic Information</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th>Name:</th>
                            <td><?php echo htmlspecialchars($userData['name'] . ' ' . $userData['surname']); ?></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><?php echo htmlspecialchars($userData['email']); ?></td>
                        </tr>
                        <tr>
                            <th>Phone Number:</th>
                            <td><?php echo htmlspecialchars($userData['phone_number']); ?></td>
                        </tr>
                        <tr>
                            <th>Role:</th>
                            <td><?php echo htmlspecialchars($userData['user_role']); ?></td>
                        </tr>
                    </table>

                    <!-- Additional Information -->
                    <h5 class="mt-4 mb-3">Additional Information</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th>Username:</th>
                            <td><?php echo htmlspecialchars($userData['client_username']); ?></td>
                        </tr>
                        <tr>
                            <th>Town:</th>
                            <td><?php echo htmlspecialchars($userData['town']); ?></td>
                        </tr>
                        <tr>
                            <th>Region:</th>
                            <td><?php echo htmlspecialchars($userData['region']); ?></td>
                        </tr>
                        <tr>
                            <th>Account Created:</th>
                            <td><?php echo htmlspecialchars($userData['created_at']); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer text-end">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
</div>

            </div>
        </div>
    </div>
</div>


<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="update_profile.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Pre-filled Form Fields -->
                    <div class="mb-3">
                        <label for="name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($userData['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="surname" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="surname" name="surname" value="<?php echo htmlspecialchars($userData['surname']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($userData['phone_number']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="town" class="form-label">Town</label>
                        <input type="text" class="form-control" id="town" name="town" value="<?php echo htmlspecialchars($userData['town']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="region" class="form-label">Region</label>
                        <input type="text" class="form-control" id="region" name="region" value="<?php echo htmlspecialchars($userData['region']); ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


            <!-- Blank End -->

<?php
include('footer.php');
?>
        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>