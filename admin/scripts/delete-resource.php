<?php
// Include your custom connection class file
include('../zon.php');

// Initialize the connection
$conn = new Con();
$db = $conn->connect();

// Check if the ID is set in the query string
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute the delete statement
    $sql = "DELETE FROM resources WHERE resource_id=?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect to the counselors management page after successful deletion
        header("Location: ..//resource-library.php");
        exit();
    } else {
        echo "Error deleting Resource: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Redirect to manage-counselors.php if no ID is provided
    header("Location: ../resource-library.php");
    exit();
}

$db->close();
?>
