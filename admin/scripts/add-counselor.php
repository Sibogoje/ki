<?php
// Include database connection
include('../con.php');
$conn = new Con();
$db = $conn->connect();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input values
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $full_name = mysqli_real_escape_string($db, $_POST['full_name']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $phone_number = mysqli_real_escape_string($db, $_POST['phone_number']);
    $credentials = mysqli_real_escape_string($db, $_POST['credentials']);
    
    // Prepare and execute the insert query
    $query = "INSERT INTO counselors (username, full_name, email, phone_number, credentials) 
              VALUES ('$username', '$full_name', '$email', '$phone_number', '$credentials')";
    
    if (mysqli_query($db, $query)) {
        // Redirect to the counselors page with a success message
        header("Location: ../counselors.php?success=1");
    } else {
        // Redirect to the counselors page with an error message
        header("Location: ../counselors.php?error=" . mysqli_error($db));
    }

    // Close the database connection
    mysqli_close($db);
} else {
    // Redirect to the counselors page if accessed directly
    header("Location: counselors.php");
}
?>
