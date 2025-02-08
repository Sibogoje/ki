<?php
// Include database connection
include 'admin/con.php';
$conn = new Con();
$db = $conn->connect();
session_start();

// Get the user ID from the session
$userID = $_SESSION['counselor_id'];

// Retrieve and sanitize POST data
$name = htmlspecialchars($_POST['name']);
$surname = htmlspecialchars($_POST['surname']);
$email = htmlspecialchars($_POST['email']);
$phone_number = htmlspecialchars($_POST['phone_number']);
$town = htmlspecialchars($_POST['town']);
$region = htmlspecialchars($_POST['region']);

// Update the user's data
$query = "UPDATE users SET name = ?, surname = ?, email = ?, phone_number = ?, town = ?, region = ? WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('ssssssi', $name, $surname, $email, $phone_number, $town, $region, $userID);

if ($stmt->execute()) {
    // Redirect back to the profile page with a success message
    $_SESSION['success'] = "Profile updated successfully!";
    header("Location: profile.php");
} else {
    // Redirect back with an error message
    $_SESSION['error'] = "Failed to update profile.";
    header("Location: profile.php");
}

exit();
?>
