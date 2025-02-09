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
    <header>
        <h2>Resources</h2>
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
</body>
</html>

<?php
$db->close();
?>
