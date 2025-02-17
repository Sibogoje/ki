<?php
include('../zon.php'); // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        // Set the target directory and file path
        $uploadFileDir = $_SERVER['DOCUMENT_ROOT'] . '/admin/uploads/';
        $dest_path = $uploadFileDir . $fileName;
        $dest_url = $universal_url . '/admin/uploads/' . $fileName;
        
        // Debugging information
        echo "<p>File temporary path: $fileTmpPath</p>";
        echo "<p>Destination path: $dest_path</p>";
        echo "<p>Upload directory: $uploadFileDir</p>";
        
        // Check if the upload directory exists and is writable
        if (!is_dir($uploadFileDir)) {
            echo "<p>Upload directory does not exist.</p>";
        } elseif (!is_writable($uploadFileDir)) {
            echo "<p>Upload directory is not writable.</p>";
        } else {
            // Move the file to the target directory
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Prepare SQL statement
                $conn = new Con();
                $db = $conn->connect();
                
                $sql = "INSERT INTO resources (title, description, file) VALUES (?, ?, ?)";
                $stmt = $db->prepare($sql);
                $stmt->bind_param('sss', $title, $description, $dest_url);
                
                // Execute the query
                if ($stmt->execute()) {
                    header('Location: ../resource-library.php');
                } else {
                    echo "<p>Error: " . $stmt->error . "</p>";
                }
                
                // Close the connection
                $stmt->close();
                $db->close();
            } else {
                echo "<p>Error moving the file.</p>";
            }
        }
    } else {
        echo "<p>No file uploaded or there was an upload error.</p>";
    }
} else {
    echo "<p>Invalid request method.</p>";
}
?>
