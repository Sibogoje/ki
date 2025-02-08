<?php
include '../con.php';
$conn = new Con();
$db = $conn->connect();
session_start();

// Get the user ID from the session
$userID = $_SESSION['client_user_id'];

// Retrieve and sanitize POST data
$name = htmlspecialchars($_POST['name']);
$surname = htmlspecialchars($_POST['surname']);
$email = htmlspecialchars($_POST['email']);
$phone_number = htmlspecialchars($_POST['phone_number']);
$age = htmlspecialchars($_POST['age']);
$gender = htmlspecialchars($_POST['gender']);
$marital = htmlspecialchars($_POST['marital']);
$orphan = htmlspecialchars($_POST['orphan']);
$education = htmlspecialchars($_POST['education']);
$disability = htmlspecialchars($_POST['disability']);
$region = htmlspecialchars($_POST['region']);
$constituency = htmlspecialchars($_POST['constituency']);
$community = htmlspecialchars($_POST['community']);
$town = htmlspecialchars($_POST['town']);

// Update the user's data
$query = "UPDATE users SET 
          name = ?, 
          surname = ?, 
          email = ?, 
          phone_number = ?, 
          age = ?, 
          gender = ?, 
          marital = ?, 
          orphan = ?, 
          education = ?, 
          disability = ?, 
          region = ?, 
          constituency = ?, 
          community = ?, 
          town = ? 
          WHERE phone_number = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('ssssssssssssssi', 
                  $name, 
                  $surname, 
                  $email, 
                  $phone_number, 
                  $age, 
                  $gender, 
                  $marital, 
                  $orphan, 
                  $education, 
                  $disability, 
                  $region, 
                  $constituency, 
                  $community, 
                  $town, 
                  $phone_number);

if ($stmt->execute()) {
    // Redirect back to the profile page with a success message
    $_SESSION['success'] = "Profile updated successfully!";
    $_SESSION['uid'] = $userID;
    header("Location: profile.php");
} else {
    // Redirect back with an error message
    $_SESSION['error'] = "Failed to update profile.";
    header("Location: profile.php");
}

$stmt->close();
$db->close();
?>
