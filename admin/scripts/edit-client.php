<?php
// Include database connection
include('../zon.php');
$conn = new Con();
$db = $conn->connect();

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $username = mysqli_real_escape_string($db, $_POST['edit_name']);
    $full_name = mysqli_real_escape_string($db, $_POST['edit_surname']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $phone_number = mysqli_real_escape_string($db, $_POST['phone_number']);

    // Update query
    $query = "UPDATE users SET 
                name = '$username', 
                surname = '$full_name', 
                email = '$email', 
                phone_number = '$phone_number' 
              WHERE user_id = '$id'";

    // Execute the query
    if (mysqli_query($db, $query)) {
        // Redirect to the main page with a success message
        header("Location: ../clients.php?update=success");
    } else {
        // Redirect to the main page with an error message
        header("Location: ../clients.php?update=error");
    }

    // Close the database connection
    mysqli_close($db);
} else {
    // Redirect if accessed without POST request
    header("Location: index.php");
}
?>
