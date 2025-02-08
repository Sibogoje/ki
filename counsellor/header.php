<?php 


// Check if the user is logged in
if (!isset($_SESSION['counselor_name']) || !isset($_SESSION['counselor_surname']) || !isset($_SESSION['counselor_role']) || $_SESSION['counselor_role'] != 'counselor') {
    // Redirect to the login page if not logged in
    header("Location: index.php");
    exit; // Ensure the script stops execution after the redirect
}

// Replace these with actual session variables
$counselor_user_name = $_SESSION['counselor_name']; 
$counselor_user_surname = $_SESSION['counselor_surname']; 
$counselor_user_role = $_SESSION['counselor_role']; 
?>
  
<!-- Sidebar Start -->
<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <a href="dashboard.php" class="navbar-brand mx-4 mb-3">
            <h4 class="text-primary"><i class="fa fa-hashtag me-2"></i>Kwakha Indvodza </h4>
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                <h6 class="mb-0"><?php echo $counselor_user_name . ' ' . $counselor_user_surname; ?></h6>
                <span><?php echo $counselor_user_role; ?></span>
            </div>
        </div>
        <div class="navbar-nav w-100">
            <a href="dashboard.php" class="nav-item nav-link"><i class="fa fa-home me-2"></i>Home</a>
            <a href="bookings.php" class="nav-item nav-link"><i class="fa fa-calendar-check me-2"></i>Bookings</a>
            <a href="notes.php" class="nav-item nav-link"><i class="fa fa-file-alt me-2"></i>Notes</a>
            <!--<a href="donate.php" class="nav-item nav-link"><i class="fa fa-donate me-2"></i>Donate</a>-->
            <a href="logout.php" class="nav-item nav-link"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
        </div>
    </nav>
</div>
<!-- Sidebar End -->
