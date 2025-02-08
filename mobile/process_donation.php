<?php
include('../con.php');
$conn = new Con();
$db = $conn->connect();

// Retrieve form data
$donor_name = $_POST['donor_name'];
$donor_phone = $_POST['donor_phone'];
$donor_email = $_POST['donor_email'];
$donation_amount = $_POST['donation_amount'];
$other_donations = $_POST['other_donations'] ?? '';
$is_pledge = isset($_POST['is_pledge']) ? 1 : 0;
$pledge_date = $_POST['pledge_date'] ?? null;
$reminder_check = isset($_POST['reminder_check']) ? 1 : 0;
$reminder_date = $_POST['reminder_date'] ?? null;
$submission_date = date('Y-m-d H:i:s');

// Validate required fields
if (empty($donor_name) || empty($donor_phone) || empty($donation_amount)) {
    die("Required fields are missing.");
}

// Insert donation into database
$stmt = $db->prepare("INSERT INTO donations 
    (donor_name, donor_phone, donor_email, donation_amount, other_donations, is_pledge, pledge_date, reminder_check, reminder_date, submission_date) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param(
    "ssssdsdsss",
    $donor_name,
    $donor_phone,
    $donor_email,
    $donation_amount,
    $other_donations,
    $is_pledge,
    $pledge_date,
    $reminder_check,
    $reminder_date,
    $submission_date
);

if ($stmt->execute()) {
    echo "Donation submitted successfully. Thank you for your generosity!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$db->close();
?>
