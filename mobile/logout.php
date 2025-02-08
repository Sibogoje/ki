<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <script>
        // Force a page reload to ensure the session changes are reflected
        window.location.href = '../index.php';
    </script>
</head>
<body>
</body>
</html>
