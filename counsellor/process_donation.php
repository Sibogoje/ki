<?php
// Database connection
include 'admin/con.php';
$conn = new Con();
$db = $conn->connect();
// Start a session
session_start();
// Retrieve form data
$donor_name = $_POST['donor_name'];
$donor_phone = $_POST['donor_phone'];
$donor_email = $_POST['donor_email'];
$donation_amount = $_POST['donation_amount'];
$other_donations = $_POST['other_donations'];
$is_pledge = isset($_POST['is_pledge']) ? 1 : 0; // Checkbox for pledge
$pledge_date = !empty($_POST['pledge_date']) ? $_POST['pledge_date'] : null;
$reminder_check = isset($_POST['reminder_check']) ? 1 : 0; // Checkbox for reminder
$reminder_date = !empty($_POST['reminder_date']) ? $_POST['reminder_date'] : null;

// Prepare and bind
$stmt = $db->prepare("INSERT INTO donations (donor_name, donor_phone, donor_email, donation_amount, other_donations, is_pledge, pledge_date, reminder_check, reminder_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssdsisss", $donor_name, $donor_phone, $donor_email, $donation_amount, $other_donations, $is_pledge, $pledge_date, $reminder_check, $reminder_date);

// Execute and check for success
if ($stmt->execute()) {
    // Redirect to donate.php with a success message
    header('Location: donate.php?success=1');
    exit; // Ensure the script stops after the redirect
} else {
    // Redirect to donate.php with an error message
    header('Location: donate.php?error=' . urlencode($stmt->error));
    exit;
}

// Close connections
$stmt->close();
$db->close();
?>
