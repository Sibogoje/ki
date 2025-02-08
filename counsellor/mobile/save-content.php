<?php
// Include the database connection file
// Include database connection
include('../con.php');
$conn = new Con();
$db = $conn->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = "INSERT INTO content (title, body, created_at) VALUES (?, ?, NOW())";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('ss', $title, $content);

    if ($stmt->execute()) {
        // Content saved successfully
        echo "<script>alert('Content saved successfully!'); window.location.href='mobile-home.phpp';</script>";
    } else {
        // Error occurred
        echo "<script>alert('Error saving content. Please try again.'); window.location.href='mobile-home.php';</script>";
    }

    $stmt->close();
}

$db->close();
?>
