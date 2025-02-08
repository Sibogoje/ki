<?php
include 'admin/zon.php';
$conn = new Con();
$db = $conn->connect();
// Start a session
session_start();

$userID = $_SESSION['counselor_id'];
$title = htmlspecialchars($_POST['note_title'] ?? '', ENT_QUOTES, 'UTF-8');
$body = htmlspecialchars($_POST['note_body'] ?? '', ENT_QUOTES, 'UTF-8');

$sql = "INSERT INTO notes (user_id, title, body) VALUES (?, ?, ?)";
$stmt = $db->prepare($sql);
$stmt->bind_param('iss', $userID, $title, $body);

if ($stmt->execute()) {
    echo "<script>alert('Note saved successfully!'); window.location.href='notes.php';</script>";
} else {
    echo "<script>alert('Error saving note. Please try again.'); window.location.href='notes.php';</script>";
}

$stmt->close();
$db->close();
?>
