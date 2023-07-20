<?php
if (!isset($_COOKIE['PHPSESSID'])) {
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
        include "./connectdb.php";
        session_start();
        $findUser_sql=$connection->prepare("SELECT * FROM `user_details` WHERE `id` = ?");
        $findUser_sql->bind_param("i",$_SESSION['id']);
        $findUser_sql->execute();
        $recordFound=mysqli_fetch_assoc($findUser_sql->get_result()); 
        echo "<h1>Hello user ->".$recordFound["username"]. "</h1>"
    ?>
    <a href="./logout.php" class="logout">LOG OUT</a>
</body>
</html>