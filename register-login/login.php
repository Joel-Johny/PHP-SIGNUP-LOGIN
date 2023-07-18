<?php
session_start();
if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit();
}
$validationResult=false;
if($_SERVER["REQUEST_METHOD"]=="POST"){
    include "./connectdb.php";

    $form_username=mysqli_real_escape_string($connection,$_POST["username"]);
    $form_password=mysqli_real_escape_string($connection,$_POST["password"]);

    // echo "$form_username $form_password $form_c_password";

    // connect to DB
    
        if($connection){

            $findUser_sql=$connection->prepare("SELECT * FROM `user_details` WHERE `user_id` = ?");
            $findUser_sql->bind_param("s",$form_username);
            $findUser_sql->execute();

            $response=$findUser_sql->get_result();
            $recordFound=mysqli_fetch_assoc($response);
            // echo var_dump($recordFound);
            if(mysqli_num_rows($response)==1){
                if(password_verify($form_password,$recordFound["password"])){
                    $_SESSION['username']=$form_username;
                    header('Location: dashboard.php');
                    exit();
                }
                else
                    $validationResult="Invalid Credentials";
            }
            else
                $validationResult="Invalid Credentials";
        }
        else    
            $validationResult="Failed connection to db";

}
   

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body class="vh-align">

    <form method="post" action="./login.php" class="flex-col-direction">
        <h3> LOGIN </h3>
        <input type="text" placeholder="Username" name="username" required>
        <input type="password" placeholder="Password" name="password" required>
        <button type="submit">LOGIN</button>
        
        <?php
            if($validationResult)
                echo "<h5 class=validate>$validationResult</h5>";
                // echo "<h5 class=\"validate\">$validationResult</h5>";
            
        ?>
        <a href="./register.php">New User?</a>

    </form>
    
</body>
</html>