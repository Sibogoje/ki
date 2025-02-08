<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../con.php');
$conn = new Con();
$db = $conn->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $surname = mysqli_real_escape_string($db, $_POST['surname']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $phone_number = mysqli_real_escape_string($db, $_POST['phone_number']);
    $role = mysqli_real_escape_string($db, $_POST['role']);
    
    $hashedPassword = password_hash($phone_number, PASSWORD_BCRYPT);

    // Insert new client into the database
    $query = "INSERT INTO users (name, surname, email, phone_number, user_role, password_hash, created_at) 
              VALUES ('$name', '$surname', '$email', '$phone_number', '$role', '$hashedPassword', NOW())";

    if (mysqli_query($db, $query)) {
        // Redirect back to the client management page
        header('Location: ../clients.php?success=Client added successfully');
    } else {
        // Display error details
        echo "Error executing query: " . mysqli_error($db);
        echo "<br>SQL Query: " . $query;
    }
}

// Close database connection
mysqli_close($db);
?>
