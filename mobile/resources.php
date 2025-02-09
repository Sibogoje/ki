<?php
session_start();
$loggedIn = isset($_SESSION['user_id']);
include '../zon.php';
$conn = new Con();
$db = $conn->connect();

$query = "SELECT `title`, `description`, `file` FROM resources";
$result = $db->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resources</title>
    <link rel="stylesheet" href="../styles.css?v=1.0">
</head>
<body>
    <header style="display: flex; align-items: center; background-color: black; padding: 10px;">
        <a href="../index.php" class="back-button py-2" style="text-decoration: none; color: green; display: flex; align-items: center;">
            <span style="font-weight: bold; margin-right: 5px;">&lt;</span>
            <span>Back</span>
        </a>
        <h2 style="flex-grow: 1; text-align: center; margin: 0; color: green;">A SAFE SPACE FOR YOU</h2>
    </header>
    <main>
        <section>
            <?php if ($result->num_rows > 0) { ?>
                <ul>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <li>
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <a href="../uploads/<?php echo htmlspecialchars($row['file']); ?>" download>Download</a>
                        </li>
                    <?php } ?>
                </ul>
            <?php } else { ?>
                <p>No resources available.</p>
            <?php } ?>
        </section>
    </main>
    <?php include "../footer.php"; ?>
    <script>
        function toggleNavDrawer() {
            const navDrawer = document.getElementById('navDrawer');
            const overlay = document.getElementById('overlay');
            navDrawer.classList.toggle('open');
            overlay.classList.toggle('active');
        }
    </script>
</body>
</html>

<?php
$db->close();
?>
