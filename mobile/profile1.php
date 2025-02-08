<?php
// Database connection
include('../con.php');
$conn = new Con();
$db = $conn->connect();
// Fetch the user data for the profile
$query = "SELECT *
          FROM users 
          WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('i', $userID);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .alert {
            display: none;
        }
        
        #profileForm{
            padding: 15px;
        }
        .form-label {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <!--<h1 class="text-center">Client Profile</h1>-->

    <!-- Success Message -->
    <div id="successAlert" class="alert alert-success text-center" role="alert">
        Profile successfully updated!
    </div>

    <form id="profileForm" >
        <!-- User Details -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="surname" class="form-label">Surname</label>
                <input type="text" class="form-control" id="surname" name="surname" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="town" class="form-label">Town</label>
                <input type="text" class="form-control" id="town" name="town">
            </div>
            <div class="col-md-6 mb-3">
                <label for="region" class="form-label">Region</label>
                <input type="text" class="form-control" id="region" name="region">
            </div>
        </div>
        <!-- More fields... -->
        
        <div class="row">
        <div class="col-md-6">
            <label for="age" class="form-label">Age</label>
            <select class="form-control" id="age" name="age">
                <option value="12-16" <?php if ($userData['age'] == '12-16') echo 'selected'; ?>>12-16</option>
                <option value="17-20" <?php if ($userData['age'] == '17-20') echo 'selected'; ?>>17-20</option>
                <option value="21-24" <?php if ($userData['age'] == '21-24') echo 'selected'; ?>>21-24</option>
                <option value="25-28" <?php if ($userData['age'] == '25-28') echo 'selected'; ?>>25-28</option>
                <option value="29-31" <?php if ($userData['age'] == '29-31') echo 'selected'; ?>>29-31</option>
                <option value="32-35" <?php if ($userData['age'] == '32-35') echo 'selected'; ?>>32-35</option>
            </select>
        </div>
            <div class="col-md-6 mb-3">
                <label for="marital" class="form-label">Marital Status</label>
                <select class="form-control" id="marital" name="marital">
                    <option value="">Select</option>
                    <option value="Single">Single</option>
                    <option value="Married">Married</option>
                    <option value="Divorced">Divorced</option>
                    <option value="Widowed">Widowed</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-control" id="gender" name="gender">
                    <option value="">Select</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="education" class="form-label">Education Level</label>
                <input type="text" class="form-control" id="education" name="education">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="orphan" class="form-label">Orphan Status</label>
                <input type="text" class="form-control" id="orphan" name="orphan">
            </div>
            <div class="col-md-6 mb-3">
                <label for="disability" class="form-label">Disability</label>
                <input type="text" class="form-control" id="disability" name="disability">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="constituency" class="form-label">Constituency</label>
                <input type="text" class="form-control" id="constituency" name="constituency">
            </div>
            <div class="col-md-6 mb-3">
                <label for="community" class="form-label">Community</label>
                <input type="text" class="form-control" id="community" name="community">
            </div>
        </div>

        <div class="text-center">
            <button type="button" id="updateProfileBtn" class="btn btn-primary w-100">Save Changes</button>
        </div>
    </form>
</div>

<?php 
$phone = $_GET['phone'];
?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const phone = "<?php echo $phone; ?>"; // Embed PHP variable into JavaScript

        // Fetch profile data
        fetch(`get_profile.php?phone=${encodeURIComponent(phone)}`)
            .then(response => response.json())
            .then(data => {
                for (let key in data) {
                    if (document.getElementById(key)) {
                        document.getElementById(key).value = data[key];
                    }
                }
            })
            .catch(error => console.error("Error loading profile data:", error));

        // Update profile
        document.getElementById("updateProfileBtn").addEventListener("click", function () {
            const formData = new FormData(document.getElementById("profileForm"));

            fetch("update_profile.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const successAlert = document.getElementById("successAlert");
                    successAlert.style.display = "block";

                    // Hide the success message after 3 seconds
                    setTimeout(() => {
                        successAlert.style.display = "none";
                    }, 3000);
                } else {
                    console.error("Update failed:", result.message);
                }
            })
            .catch(error => console.error("Error updating profile:", error));
        });
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
