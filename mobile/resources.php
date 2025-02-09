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
    <style>
        .resource-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 100px;
        }
        .resource-item {
            display: flex;
            flex-direction: column;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .resource-item h3 {
            margin: 0 0 5px 0;
            font-size: 18px;
        }
        .resource-item p {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #666;
        }
        .resource-item a {
            display: block;
            text-align: center;
            padding: 10px;
            background-color: green;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .resource-item a:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>
    <header style="display: flex; align-items: center; background-color: black; ">
        <a href="../index.php" class="back-button py-2" style="text-decoration: none; color: green; display: flex; align-items: center;">
            <span style="font-weight: bold; margin-right: 5px;">&lt;</span>
            <span>Back</span>
        </a>
        <h4 style="flex-grow: 1; text-align: center; margin: 0; color: green;">A SAFE SPACE FOR YOU</h4>
    </header>
    <main2>
        <section>
            <?php if ($result->num_rows > 0) { ?>
                <ul class="resource-list">
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <li class="resource-item">
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
    </main2>
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
