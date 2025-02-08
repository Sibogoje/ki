<?php
include('../zon.php');
$conn = new Con();
$db = $conn->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $message = $_POST['message'];

    $sql = "INSERT INTO notifications (title, message) VALUES ('$title', '$message')";
    if ($db->query($sql) === TRUE) {
        echo "Notification added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $db->error;
    }

    // Redirect back to notifications page
    header("Location: ../notifications.php");
    exit();
}
?>
