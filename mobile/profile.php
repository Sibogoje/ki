<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the user is not logged in
    header('Location: login-page.php');
    exit();
}
include '../zon.php';
$conn = new Con();
$db = $conn->connect();

if ($phone == null) {
    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $userData = $stmt->get_result()->fetch_assoc();

}
// } else {
//     $query = "SELECT * FROM users WHERE user_id = ?";
//     $stmt = $db->prepare($query);
//     $stmt->bind_param('i', $phone);
//     $stmt->execute();
//     $userData = $stmt->get_result()->fetch_assoc();
//     $_SESSION['client_user_id'] = $userData['user_id'];
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - SAFE SPACE FOR YOU</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../styles.css">
    <link rel="manifest" href="../manifest.json">
    <link rel="icon" href="../icon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background-color: black;
            display: flex;
            align-items: center;
            padding: 10px;
            color: green;
        }
        header h2 {
            margin: 0;
            font-size: 16px;
            color: green;
            flex-grow: 1;
            text-align: center;
            padding: 15px;
        }
        .back-button {
            color: green;
            font-size: 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .back-button img {
            width: 24px;
            height: 24px;
            margin-right: 5px;
        }
        .back-button::before {
            content: '<';
            font-weight: bold;
            margin-right: 5px;
            color: green;
        }
        main {
            margin-top: 60px; /* Adjust this value based on the height of your header */
            width: 100%; /* Make the main container full width */
            margin-bottom: 100px; /* Add margin to ensure content is not hidden behind the footer */
            
        }
        .back-button-container {
            position: relative;
            z-index: 1100;
        }
        .card {
            margin: 10px;
        }
        .main2 {
            margin-top: 120px; /* Adjust this value to ensure content appears below the header */
            margin-bottom: 140px; /* Add margin to ensure content is not hidden behind the footer */
            width: 100%; /* Make the main container full width */
            color: black; /* Keep content text color black */
            padding-top: 100px; /* Add padding to the top of the main container */
            padding-bottom: 100px; /* Add padding to the bottom of the main container */
        }
    </style>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('../service-worker.js')
                .then((registration) => {
                    console.log('Service Worker registered with scope:', registration.scope);
                })
                .catch((error) => {
                    console.log('Service Worker registration failed:', error);
                });
        }
    </script>
</head>
<body>
 <header>
        <a href="../index.php" class="back-button">Back</a>
        <h2>A SAFE SPACE FOR YOU</h2>
    </header>

    <main2 class="main2 container mt-1 mb-1">
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Content Start -->
        <div class="content">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="container-fluid pt-2 px-2">
                <div class="row bg-light rounded align-items-center justify-content-center mx-0">
                    <div class="col-md-12 mb-8" style="padding: 5px;">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                Profile Information
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
                            </div>
                            <div class="card-body">
                                <!-- Basic Information -->
                                <h5 class="mb-3">Basic Information</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <th>Name:</th>
                                        <td><?php echo htmlspecialchars($userData['name']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Surname:</th>
                                        <td><?php echo htmlspecialchars($userData['surname']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Phone Number:</th>
                                        <td><?php echo htmlspecialchars($userData['phone_number']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td><?php echo htmlspecialchars($userData['email']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Town:</th>
                                        <td><?php echo htmlspecialchars($userData['town']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Region:</th>
                                        <td><?php echo htmlspecialchars($userData['region']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Age:</th>
                                        <td><?php echo htmlspecialchars($userData['age']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Marital Status:</th>
                                        <td><?php echo htmlspecialchars($userData['marital']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Gender:</th>
                                        <td><?php echo htmlspecialchars($userData['gender']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Education:</th>
                                        <td><?php echo htmlspecialchars($userData['education']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Orphan:</th>
                                        <td><?php echo htmlspecialchars($userData['orphan']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Disability:</th>
                                        <td><?php echo htmlspecialchars($userData['disability']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Constituency:</th>
                                        <td><?php echo htmlspecialchars($userData['constituency']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Community:</th>
                                        <td><?php echo htmlspecialchars($userData['community']); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content End -->
    </main2>
    <footer class="bg-black text-white text-center py-3">
    <img src="../fnb foundation.png" alt="Image 3" class="mx-2" style="width: 64px; height: 45px;">
    <img src="../icon.png" alt="Image 2" class="mx-2" style="width: 45px; height: 45px;">    
    <img src="../ki.png" alt="Image 1" class="mx-2" style="width: 45px; height: 45px;">
    </footer>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="update_profile.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($userData['name']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="surname" class="form-label">Surname</label>
                            <input type="text" class="form-control" id="surname" name="surname" value="<?php echo htmlspecialchars($userData['surname']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($userData['phone_number']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="town" class="form-label">Town</label>
                            <input type="text" class="form-control" id="town" name="town" value="<?php echo htmlspecialchars($userData['town']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="region" class="form-label">Region</label>
                            <select class="form-control" id="region" name="region">
                                <option value="Hhohho" <?php if ($userData['region'] == 'Hhohho') echo 'selected'; ?>>Hhohho</option>
                                <option value="Manzini" <?php if ($userData['region'] == 'Manzini') echo 'selected'; ?>>Manzini</option>
                                <option value="Lubombo" <?php if ($userData['region'] == 'Lubombo') echo 'selected'; ?>>Lubombo</option>
                                <option value="Shiselweni" <?php if ($userData['region'] == 'Shiselweni') echo 'selected'; ?>>Shiselweni</option>
                            </select>
                        </div>
                        <div class="mb-3">
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
                        <div class="mb-3">
                            <label for="marital" class="form-label">Marital Status</label>
                            <select class="form-control" id="marital" name="marital">
                                <option value="Married" <?php if ($userData['marital'] == 'Married') echo 'selected'; ?>>Married</option>
                                <option value="Single" <?php if ($userData['marital'] == 'Single') echo 'selected'; ?>>Single</option>
                                <option value="Divorced" <?php if ($userData['marital'] == 'Divorced') echo 'selected'; ?>>Divorced</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-control" id="gender" name="gender">
                                <option value="Male" <?php if ($userData['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                                <option value="Female" <?php if ($userData['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                                <option value="Other" <?php if ($userData['gender'] == 'Other') echo 'selected'; ?>>Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="education" class="form-label">Education</label>
                            <select class="form-control" id="education" name="education">
                                <option value="Primary" <?php if ($userData['education'] == 'Primary') echo 'selected'; ?>>Primary</option>
                                <option value="Secondary" <?php if ($userData['education'] == 'Secondary') echo 'selected'; ?>>Secondary</option>
                                <option value="Tertiary" <?php if ($userData['education'] == 'Tertiary') echo 'selected'; ?>>Tertiary</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="orphan" class="form-label">Orphan</label>
                            <select class="form-control" id="orphan" name="orphan">
                                <option value="Orphaned" <?php if ($userData['orphan'] == 'Orphaned') echo 'selected'; ?>>Orphaned</option>
                                <option value="Not Orphaned" <?php if ($userData['orphan'] == 'Not Orphaned') echo 'selected'; ?>>Not Orphaned</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="disability" class="form-label">Disability</label>
                            <select class="form-control" id="disability" name="disability">
                                <option value="Yes" <?php if ($userData['disability'] == 'Yes') echo 'selected'; ?>>Yes</option>
                                <option value="No" <?php if ($userData['disability'] == 'No') echo 'selected'; ?>>No</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="constituency" class="form-label">Constituency</label>
                            <input type="text" class="form-control" id="constituency" name="constituency" value="<?php echo htmlspecialchars($userData['constituency']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="community" class="form-label">Community</label>
                            <input type="text" class="form-control" id="community" name="community" value="<?php echo htmlspecialchars($userData['community']); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#editProfileModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var modal = $(this);
                // You can add any additional JavaScript here if needed
            });
        });
    </script>
</body>
</html>