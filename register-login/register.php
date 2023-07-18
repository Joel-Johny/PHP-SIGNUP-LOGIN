<?php


session_start();
if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit();
}

include "./connectdb.php";

$validationResult=false;
if($_SERVER["REQUEST_METHOD"]=="POST"){

    $form_username=mysqli_real_escape_string($connection,$_POST["username"]) ;
    $form_email=mysqli_real_escape_string($connection,$_POST["email"]);
    $email_pattern = '/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/';
    $whiteSpace='/\s/';
    // $form_username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    // $form_email = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    // echo "$form_username,$form_email <br>";

    $form_password=mysqli_real_escape_string($connection,$_POST["password"]);
    $form_c_password=mysqli_real_escape_string($connection,$_POST["c-password"]);

    // echo "$form_username $form_password $form_c_password";

    if (empty($form_email)) 
        $validationResult = "Email address can't be empty";
    elseif (preg_match($whiteSpace, $form_email))
        $validationResult="Please provide a email address without spaces ";
    elseif (!preg_match($email_pattern, $form_email))
        $validationResult="Please enter valid email address";

    elseif(empty($form_username)) 
        $validationResult = "Username can't be empty";
    elseif (preg_match($whiteSpace, $form_username))
        $validationResult="Please provide a username without spaces ";
    elseif(strlen($form_username)<4)
        $validationResult="Minimum length of username should be 4";

    
    elseif (empty($form_email)) 
        $validationResult = "Email address can't be empty";
    elseif (preg_match($whiteSpace, $form_email))
        $validationResult="Please provide a email address without spaces ";
    elseif (!preg_match($email_pattern, $form_email))
        $validationResult="Please enter valid email address";
    
    elseif(strlen($form_password)<8)
        $validationResult="Minimum password length should be 8";
    
    elseif($form_password!=$form_c_password)
        $validationResult="Passwords do not match Please try again!";

    else{

        if($connection){
            // echo "Successful connection to db <br>";

            $duplicate_user_sql = $connection->prepare("SELECT *, 
            CASE 
              WHEN `email` = ? THEN 'Email Address'
              WHEN `user_id` = ? THEN 'Username'
            END AS duplicate_source 
            FROM `user_details` 
            WHERE `email` = ? OR `user_id` = ?");
            $duplicate_user_sql->bind_param("ssss", $form_email , $form_username , $form_email , $form_username);
            $duplicate_user_sql->execute();
            $result = $duplicate_user_sql->get_result();
            if(mysqli_num_rows($result)==0){
    
                $hashedPassword = password_hash($form_password, PASSWORD_DEFAULT);
                // here 2
    
                $savetoDb=$connection->prepare("INSERT INTO `user_details` (`email`, `user_id`, `password`) VALUES (?,?,?);");
                $savetoDb->bind_param("sss",$form_email,$form_username,$hashedPassword);
                $savetoDb->execute();

                if($savetoDb)
                    $validationResult="Account created successfully.You can proceed to login !";
                else
                    $validationResult="Something went wrong. Please try again !".$connection->connect_error;
            }
    
            else{
                $duplicate = mysqli_fetch_assoc($result)['duplicate_source'];
                $validationResult="$duplicate already exists!";
            }
        }
        else    
            $validationResult="Something went wrong. Please try again !";
    }

}
   

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body class="vh-align">

    <form method="post" action="./register.php" class="flex-col-direction">
        <h3> REGISTER </h3>
        <input type="email" placeholder="Email" name="email" required>
        <input type="text" placeholder="Username" name="username" required>
        <input type="password" placeholder="Password" name="password" required>
        <input type="password" placeholder="Confirm Password" name="c-password" required>
        <button type="submit">REGISTER</button>

        <?php
            if($validationResult)
                echo "<h5 class=validate>$validationResult</h5>";
                // echo "<h5 class=\"validate\">$validationResult</h5>";
            
        ?>

        <a href="./login.php">Already a User?</a>

    </form>
    
</body>
</html>