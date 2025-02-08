<?php
session_start();
$loggedIn = isset($_SESSION['user_id']); // Check if user_id is set in session
$user = null;

if ($loggedIn) {
    include 'zon.php';
    $conn = new Con();
    $db = $conn->connect();
    $query = "SELECT `name`, `surname` FROM users WHERE user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    }
    $stmt->close();
    $db->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAFE SPACE FOR YOU</title>
    <link rel="stylesheet" href="styles.css?v=1.0">
    <link rel="manifest" href="manifest.json">
    <link rel="icon" href="icon.png" type="image/png">
    <style>
        .notification {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ff9800;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
.main2 {
            margin-top: 120px; /* Adjust this value to ensure content appears below the header */
            margin-bottom: 100px; /* Add margin to ensure content is not hidden behind the footer */
            width: 100%; /* Make the main container full width */
            color: black; /* Keep content text color black */
            padding-top: 100px; /* Add padding to the top of the main container */
        }

header {
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    width: 100%;
    top: 0;
    background-color: black;
    padding: 20px;
    z-index: 1000;
    margin-bottom: 80px;
}

.header-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    position: relative;
}

.hamburger {
    position: absolute;
    left: 10px;
    font-size: 24px;
    cursor: pointer;
    color: white;
}

header h2 {
    margin: 0;
    color: green;
    font-size: 20px;
    text-align: center;
}


.main-content {
    margin-top: 100px; /* Reduce this value to decrease space */
}


.nav-drawer {
    position: fixed;
    top: 0;
    left: -250px;
    width: 250px;
    height: 100%;
    background-color: #000000;
    color: white;
    transition: left 0.3s ease;
    z-index: 1001;
}

.nav-drawer.open {
    left: 0;
}

.nav-drawer ul {
    list-style: none;
    padding: 0;
    margin: 0;
    padding-top: 60px;
}

.nav-drawer ul li {
    padding: 15px;
    border-bottom: 1px solid #444;
}

.nav-drawer ul li a {
    color: white;
    text-decoration: none;
    display: block;
}

.overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.overlay.active {
    display: block;
}


.drawer-logo {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.drawer-logo img {
    width: 100px;
    height: auto;
}
      


    </style>
</head>
<body>
    <div id="notification" class="notification"></div>
<header>
    <div class="header-container">
        <div class="hamburger" onclick="toggleNavDrawer()">&#9776;</div>
        <h2>
            <?php if ($loggedIn && $user) { ?>
                Welcome - <?php echo htmlspecialchars($user['name']); ?>
            <?php } else { ?>
                A SAFE SPACE FOR YOU
            <?php } ?>
        </h2>
    </div>
</header>


    <!-- Navigation Drawer -->
    <div class="nav-drawer" id="navDrawer">
        <div class="drawer-logo">
            <img src="icon.png" alt="Company Logo">
        </div>
        <ul>
            <li><a href="mobile/news-updates.php">News & Updates</a></li>
            <li><a href="mobile/appointments.php">Appointments</a></li>
            <li><a href="mobile/messages.php">Messages</a></li>
            <li><a href="mobile/donate.php">Donate</a></li>
            <li><a href="mobile/profile.php">Settings</a></li>
            <?php if ($loggedIn) { ?>
                <li><a href="mobile/logout.php">Logout</a></li>
            <?php } else { ?>
                <li><a href="mobile/login-page.php">Sign In/Sign Up</a></li>
            <?php } ?>
        </ul>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay" onclick="toggleNavDrawer()"></div>

    <main2 class="main-content">
        <section class="grid-container">
            <a href="mobile/news-updates.php" class="section-button">
                <img src="news.png" alt="News & Updates">
                NEWS & UPDATES
            </a>
            <a href="mobile/appointments.php" class="section-button" onclick="if (!window.loggedIn) { showNotification('Please log in first'); return false; }">
                <img src="appointment.png" alt="Appointments">
                APPOINTMENTS
            </a>
            <a href="mobile/messages.php" class="section-button" onclick="if (!window.loggedIn) { showNotification('Please log in first'); return false; }">
                <img src="messages.png" alt="Messages">
                MESSAGES
            </a>
            <a href="mobile/donate.php" class="section-button">
                <img src="donate.png" alt="Donate">
                DONATE
            </a>
            <a href="mobile/profile.php" class="section-button" onclick="if (!window.loggedIn) { showNotification('Please log in first'); return false; }">
                <img src="settings.png" alt="Settings">
                SETTINGS
            </a>
            <?php if ($loggedIn) { ?>
                <a href="mobile/logout.php" class="section-button">
                    <img src="sign in.png" alt="Logout">
                    LOGOUT
                </a>
            <?php } else { ?>
                <a href="mobile/login-page.php" class="section-button">
                    <img src="sign in.png" alt="Sign In/Sign Up">
                    SIGN IN/SIGN UP
                </a>
            <?php } ?>
        </section>
    </main2>

    <footer class="bg-dark text-white text-center py-3">
        <img src="fnb foundation.png" alt="Image 3" class="mx-2" style="width: 64px; height: 45px;">
        <img src="icon.png" alt="Image 2" class="mx-2" style="width: 45px; height: 45px;">    
        <img src="ki.png" alt="Image 1" class="mx-2" style="width: 45px; height: 45px;">
    </footer>

    <script>
        // Set the window.loggedIn variable based on PHP session
        window.loggedIn = <?php echo json_encode($loggedIn); ?>;

        // Unregister service worker and clear cache
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then((registrations) => {
                registrations.forEach((registration) => {
                    registration.unregister();
                });
            });

            caches.keys().then((cacheNames) => {
                cacheNames.forEach((cacheName) => {
                    caches.delete(cacheName);
                });
            });
        }

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then((registration) => {
                    console.log('Service Worker registered with scope:', registration.scope);
                })
                .catch((error) => {
                    console.log('Service Worker registration failed:', error);
                });
        }

        function showNotification(message) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.style.display = 'block';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 4000);
        }

        function toggleNavDrawer() {
            const navDrawer = document.getElementById('navDrawer');
            const overlay = document.getElementById('overlay');
            navDrawer.classList.toggle('open');
            overlay.classList.toggle('active');
        }

       
    </script>
</body>
</html>