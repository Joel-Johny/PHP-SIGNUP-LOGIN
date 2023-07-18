<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./style.css">

</head>
<body class="vh-align flex-col-direction">
    <?php
    echo "<h1>Hello {$_SESSION["username"]}</h1>";
    ?>
    <a href="./logout.php" class="logout">LOG OUT</a>
</body>
</html>