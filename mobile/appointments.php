<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login-page.php');
    exit();
}
include '../zon.php';
$conn = new Con();
$db = $conn->connect();

$user_id = $_SESSION['user_id'];

// Fetch bookings for the logged-in user
$bookings = [];
$query = "SELECT b.booking_id, u.name AS counselor_name, u.surname AS counselor_surname, b.booking_date, b.mode, b.status 
          FROM bookings b
          JOIN users u ON b.counselor_id = u.user_id
          WHERE b.user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}
$stmt->close();

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $counselor_id = $_POST['counselor_id'];
    $booking_date = $_POST['booking_date'];
    $mode = $_POST['mode'];

    // Insert booking into the bookings table
    $query = "INSERT INTO bookings (user_id, counselor_id, booking_date, mode) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param('iiss', $user_id, $counselor_id, $booking_date, $mode);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Booking successful!';
    } else {
        $_SESSION['error'] = 'Failed to make booking. Please try again.';
    }

    $stmt->close();
    header('Location: appointments.php');
    exit();
}


?>

<script>
    // Disappear feedback after 5 seconds
    setTimeout(function() {
        var feedback = document.getElementById('booking-feedback');
        if (feedback) {
            feedback.style.display = 'none';
        }
    }, 5000);
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APPOINTMENTS</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../styles.css">
    <link rel="manifest" href="../manifest.json">
    <link rel="icon" href="../icon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
    <style>
        header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background-color: black; /* Header background color is black */
        }
        header h1 a {
            color: green !important; /* Header text color is green */
        }
        .main2 {
            margin-top: 80px; /* Adjust this value to ensure content appears below the header */
            margin-bottom: 100px; /* Add margin to ensure content is not hidden behind the footer */
            width: 100%; /* Make the main container full width */
            color: black; /* Keep content text color black */
            padding-top: 20px; /* Add padding to the top of the main container */
        }
        .form-label {
            font-weight: bold;
        }
        .btn {
            background-color: rgb(27, 4, 129); /* Button background color is green */
            border-color: green; /* Button border color is green */
        }
        .card {
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .fab {
            position: fixed;
            bottom: 110px;
            right: 20px;
            z-index: 1000;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: rgb(27, 4, 129);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }
        .modal-dialog {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <header>
        <h2><a href="../index.php" class="text-white text-decoration-none">&#x21A9; Back</a> A SAFE SPACE FOR YOU</h2>
    </header>

    <main class="main2 container-fluid mt-2" style="padding-bottom: 30px">
        <div class="row">
            <?php if (!empty($bookings)): ?>
                <?php foreach ($bookings as $booking): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                Booking ID: <?php echo htmlspecialchars($booking['booking_id']); ?>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($booking['counselor_name'] . ' ' . $booking['counselor_surname']); ?></h5>
                                <p class="card-text">
                                    <strong>Date:</strong> <?php echo htmlspecialchars($booking['booking_date']); ?><br>
                                    <strong>Status:</strong> <span class="badge bg-<?php echo $booking['status'] === 'confirmed' ? 'success' : ($booking['status'] === 'pending' ? 'warning' : 'danger'); ?>"><?php echo htmlspecialchars($booking['status']); ?></span><br>
                                    <strong>Mode:</strong> <?php echo htmlspecialchars($booking['mode']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        No bookings found.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Floating Action Button -->
    <div class="fab" data-bs-toggle="modal" data-bs-target="#addBookingModal">
        +
    </div>

    <!-- Add Booking Modal -->
    <div class="modal fade" id="addBookingModal" tabindex="-1" aria-labelledby="addBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBookingModalLabel">Add New Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addBookingForm" action="" method="POST">
                        <div class="mb-3">
                            <label for="counselor_id" class="form-label">Counselor</label>
                            <select class="form-select" id="counselor_id" name="counselor_id" required>
                                <option value="">Select Counselor</option>
                                <?php
                                // Fetch counselors from the database
                                $query = "SELECT `user_id`, `name`, `surname` FROM `users` WHERE user_role = 'counselor' ";
                                $result = $db->query($query);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='{$row['user_id']}'>{$row['name']} {$row['surname']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="booking_date" class="form-label">Booking Date</label>
                            <input type="datetime-local" class="form-control" id="booking_date" name="booking_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="mode" class="form-label">Mode</label>
                            <select class="form-select" id="mode" name="mode" required>
                                <option value="">Select Mode</option>
                                <option value="Online">Online</option>
                                <option value="Offline">Offline</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3">
    <img src="../fnb foundation.png" alt="Image 3" class="mx-2" style="width: 64px; height: 45px;">
    <img src="../icon.png" alt="Image 2" class="mx-2" style="width: 45px; height: 45px;">    
    <img src="../ki.png" alt="Image 1" class="mx-2" style="width: 45px; height: 45px;">
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

<?php 
$db->close();
?>
</html>