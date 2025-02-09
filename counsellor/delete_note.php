<?php
// Include the database connection file
include 'admin/zon.php';
$conn = new Con();
$db = $conn->connect();
// Start a session
session_start();

$noteID = isset($_GET['note_id']) ? intval($_GET['note_id']) : 0;
$userID = $_SESSION['counselor_id'];

// Delete the note from the database
$query = "DELETE FROM notes WHERE note_id = ? AND user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('ii', $noteID, $userID);

if ($stmt->execute()) {
    $_SESSION['success'] = 'Note deleted successfully!';
} else {
    $_SESSION['error'] = 'Failed to delete note.';
}

$stmt->close();
$db->close();

header('Location: notes.php');
exit();
?>
