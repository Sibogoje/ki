<?php
include '../zon.php';
$conn = new Con();
$db = $conn->connect();
session_start();

$news_id = $_GET['id'];

// Fetch news details
$query = "SELECT * FROM news WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('i', $news_id);
$stmt->execute();
$result = $stmt->get_result();
$news = $result->fetch_assoc();
$stmt->close();
$db->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($news['title']); ?> - Kwakha Indvodza</title>
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
            background-color: black;
            display: flex;
            align-items: center;
            padding: 10px;
            color: green;
        }
        .back-button {
            color: green;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .back-button::before {
            content: '<';
            font-weight: bold;
            margin-right: 5px;
        }
        header h2 {
            margin: 0;
            font-size: 16px;
            color: green;
            flex-grow: 1;
            text-align: center;
        }
        main {
            margin-top: 70px; /* Adjust this value to ensure content appears below the header */
            width: 100%; /* Make the main container full width */
            color: black; /* Keep content text color black */
        }
        .form-label {
            font-weight: bold;
        }
        .main2 {
            margin-top: 120px; /* Adjust this value to ensure content appears below the header */
            margin-bottom: 140px; /* Add margin to ensure content is not hidden behind the footer */
            width: 100%; /* Make the main container full width */
            color: black; /* Keep content text color black */
            padding-top: 100px; /* Add padding to the top of the main container */
            padding-bottom: 100px; /* Add padding to the bottom of the main container */
        }
        .card-img-top {
            width: 100%;
            height: 200px; /* Set a reasonable height */
            object-fit: cover; /* Ensure the image covers the area */
        }
    </style>
</head>
<body>
 <header>
        <a href="news-updates.php" class="back-button py-2">Back</a>
        <h2>News Detail</h2>
    </header>

    <main2 class="main2 container-fluid mt-2" style="padding-bottom: 30px">
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card">
                    <img src="../<?php echo htmlspecialchars($news['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($news['title']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($news['title']); ?></h5>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($news['full_description'])); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </main2>

<?php include "../footer.php"; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
