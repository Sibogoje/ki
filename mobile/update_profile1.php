<?php
include('../zon.php');
$conn = new Con();
$db = $conn->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $surname = $_POST['surname'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone_number = $_POST['phone_number'];
$town = $_POST['town'];
$region = $_POST['region'];
$age = $_POST['age'];
$marital = $_POST['marital'];
$gender = $_POST['gender'];
$education = $_POST['education'];
$orphan = $_POST['orphan'];
$disability = $_POST['disability'];
$constituency = $_POST['constituency'];
$community = $_POST['community'];

   $sql = "UPDATE users SET 
            surname = ?, name = ?, email = ?, phone_number = ?, town = ?, region = ?, 
            age = ?, marital = ?, gender = ?, education = ?, orphan = ?, 
            disability = ?, constituency = ?, community = ? 
        WHERE phone_number = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("sssssssssssssss", 
    $surname, $name, $email, $phone_number, $town, $region, 
    $age, $marital, $gender, $education, $orphan, 
    $disability, $constituency, $community, $phone_number);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => $db->error]);
    }

    $stmt->close();
    $db->close();
}
?>
