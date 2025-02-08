<?php
include 'zon.php';
session_start();

// Database connection
$conn = new Con();
$db = $conn->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'];
    $short_description = $_POST['short_description'];
    $full_content = $_POST['full_content'];
    $image = $_FILES['image']['name'] ? 'uploads/' . basename($_FILES['image']['name']) : null;
    $url = $_FILES['image']['name'] ? 'admin/uploads/' . basename($_FILES['image']['name']) : null;

    // Handle file upload if an image is provided
    if ($image) {
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    if ($_POST['action'] == 'create') {
        // Insert new record into the table
        $query = "INSERT INTO news_updates (title, short_description, full_content, image_url) 
                  VALUES ('$title', '$short_description', '$full_content', '$url')";
    } else {
        // Update an existing record
        $query = "UPDATE news_updates 
                  SET title='$title', short_description='$short_description', full_content='$full_content'";
        if ($image) {
            $query .= ", image_url='$url'";
        }
        $query .= " WHERE id='$id'";
    }

    if ($db->query($query)) {
        header('Location: news.php');
        exit();
    } else {
        echo "Error: " . $db->error;
    }
}

// Handle delete operation
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM news_updates WHERE id='$id'";
    if ($db->query($query)) {
        header('Location: news.php');
        exit();
    } else {
        echo "Error: " . $db->error;
    }
}
?>

