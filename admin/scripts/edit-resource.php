<?php
include('../con.php'); // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $resource_id = $_GET['id'];
    
    $conn = new Con();
    $db = $conn->connect();

    // Fetch the existing resource details
    $sql = "SELECT * FROM resources WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $resource_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $resource = $result->fetch_assoc();

    if (!$resource) {
        echo "<p>Resource not found.</p>";
        exit();
    }

    // Handle file upload if a new file is provided
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $fileToUpload = $_FILES['file']['tmp_name'];

        // File upload logic
        if (!empty($fileToUpload)) {
            $fileName = $_FILES['file']['name'];
            $uploadFileDir = 'uploads/';
            $dest_path = $uploadFileDir . $fileName;
            
            if (move_uploaded_file($fileToUpload, $dest_path)) {
                $filePath = $dest_path;
            } else {
                echo "<p>Error moving the file.</p>";
                exit();
            }
        } else {
            // Keep the existing file if no new file is uploaded
            $filePath = $resource['file'];
        }

        // Update resource details in the database
        $sql = "UPDATE resources SET title = ?, description = ?, file = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('sssi', $title, $description, $filePath, $resource_id);

        if ($stmt->execute()) {
            echo "<p>Resource updated successfully.</p>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }
        
        $stmt->close();
        $db->close();
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Edit Resource</title>
    <!-- Include Bootstrap CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Edit Resource</h2>
        <form action="edit-resource.php?id=<?php echo htmlspecialchars($resource_id); ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($resource['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($resource['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">File</label>
                <input type="file" class="form-control" id="file" name="file">
                <?php if (!empty($resource['file'])): ?>
                    <a href="<?php echo htmlspecialchars($resource['file']); ?>" target="_blank">Current File</a>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Update Resource</button>
        </form>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
