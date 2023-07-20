<?php
if (isset($_COOKIE['PHPSESSID'])) {
    header('Location: dashboard.php');
    exit();
}
$validationResult=false;
if($_SERVER["REQUEST_METHOD"]=="POST"){
    include "./connectdb.php";

    $form_username=$_POST["username"];
    $form_password=$_POST["password"];
    $ajax_response=array("success"=>false);
    // echo "$form_username $form_password $form_c_password";

    // connect to DB
    
        if($connection){

            $findUser_sql=$connection->prepare("SELECT * FROM `user_details` WHERE `username` = ?");
            $findUser_sql->bind_param("s",$form_username);
            $findUser_sql->execute();
            $response=$findUser_sql->get_result();
            $recordFound=mysqli_fetch_assoc($response);
            // echo ($recordFound["id"]);
            if(mysqli_num_rows($response)==1){
                if(password_verify($form_password,$recordFound["password"])){
                    //here
                    session_start();
                    // echo("<script>console.log('PHP session id: " . session_id() . "');</script>");
                    $_SESSION['id']=$recordFound["id"];
                    // header('Location: dashboard.php');
                    $ajax_response["success"]=true;

                }
                else
                    $ajax_response["login-err"]="Please check username and password!";//if user if bound but incorrect pass
                    
            }
            else
                $ajax_response["login-err"]="Please check username and password!";//if username does not exist
        }
        else    
            $ajax_response["login-err"]="Something went wrong!";//problem connecting to db
        
        echo(json_encode($ajax_response));
        exit();

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

    <form method="post" action="./login.php" class="flex-col-direction" id="login-form">
        <h3> LOGIN </h3>
        <input type="text" placeholder="Username" name="username" required>
        <input type="password" placeholder="Password" name="password" required>
        <button type="submit">LOGIN</button>
        <span class="validate login-err"></span>

        <a href="./register.php">New User?</a>

    </form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script src="./index.js"></script>
</body>
</html>