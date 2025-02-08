<?php
include('../zon.php'); // Ensure correct path to your database connection file

// Retrieve form data
$appointment_id = $_POST['appointment_id'];
$note = $_POST['note'];

// Create a new instance of the Con class and connect to the database
$conn = new Con();
$db = $conn->connect();

// Prepare the SQL query to insert the new session note
$sql = "INSERT INTO session_notes (appointment_id, note, created_at) VALUES (?, ?, NOW())";
$stmt = $db->prepare($sql);

// Bind the parameters and execute the query
$stmt->bind_param('is', $appointment_id, $note);
if ($stmt->execute()) {
    // Redirect to the session notes page with a success message
    header("Location: ../session-notes.php?success=Note added successfully");
} else {
    // Redirect to the session notes page with an error message
    header("Location: ../session-notes.php?error=Failed to add note");
}

// Close the statement and connection
$stmt->close();
$db->close();
?>
