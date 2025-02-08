<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
   
    header("Location: login.php");
    exit;
}
 $username = $_SESSION['name'];
 $userID = $_SESSION['user_id'];
// Example content for the logged-in user
//echo "Welcome, " . htmlspecialchars($_SESSION['username']) . "!";
?>