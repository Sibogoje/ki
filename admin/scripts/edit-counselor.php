<?php
// Include your custom connection class file
include('../zon.php');

// Initialize the connection
$conn = new Con();
$db = $conn->connect();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $id = $_POST['id'];
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $credentials = $_POST['credentials'];

    // Update the counselor's information in the database
    $sql = "UPDATE counselors SET username=?, full_name=?, email=?, phone_number=?, credentials=? WHERE id=?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sssssi", $username, $full_name, $email, $phone_number, $credentials, $id);

    if ($stmt->execute()) {
        // Redirect to the counselors management page after successful update
        header("Location: ../counselors.php");
        exit();
    } else {
        echo "Error updating counselor: " . $stmt->error;
    }

    $stmt->close();
}

?>