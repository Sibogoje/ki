<?php 
// Include database connection
include('zon.php');
$conn = new Con();
$db = $conn->connect();

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// ini_set('log_errors', 1);
// ini_set('error_log', '/path/to/your/php-error.log');
// error_reporting(E_ALL);


$id = $_GET['id'];

$sql = "DELETE FROM users WHERE user_id = ?";

// Prepare the SQL statement
$stmt = $db->prepare($sql);

// Execute the prepared statement with the user ID as a parameter
$stmt->bind_param("i", $id);
$stmt->execute();

//echo "User with ID $id has been deleted.";

// Redirect back to the index page
header('Location: clients.php');
exit;
?>
