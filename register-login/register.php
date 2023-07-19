<?php
include "./validations.php";

if (isset($_COOKIE['PHPSESSID']))  {
    header('Location: dashboard.php');
    exit();
}

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $form_username=trim($_POST["username"]);
    $form_email=$_POST["email"];
    $form_password=$_POST["password"];
    $form_c_password=$_POST["c-password"];

    $validation_result=validations($form_email,$form_username,$form_password,$form_c_password);
    $ajax_response=array("success"=>false);
    // echo var_dump($validation_result)."<br>";
    // echo var_dump($validation_result["username"])."<br>";
    // echo var_dump($validation_result["password"])."<br>";
    // echo var_dump($validation_result["error_count"])."<br>";

    if(count($validation_result)==0){

        include "./connectdb.php";
        if($connection){
            // echo "Successful connection to db <br>";
            // $duplicate_user_sql = $connection->prepare("SELECT *, 
            // CASE 
            //   WHEN `email` = ? THEN 'Email Address'
            //   WHEN `user_id` = ? THEN 'Username'
            // END AS duplicate_source 
            // FROM `user_details` 
            // WHERE `email` = ? OR `user_id` = ?");

            $duplicate_user_sql =$connection->prepare("SELECT * FROM user_details WHERE email = ? OR username = ?;");
            $duplicate_user_sql->bind_param("ss", $form_email , $form_username);
            $duplicate_user_sql->execute();
            $result = $duplicate_user_sql->get_result();
            if(mysqli_num_rows($result)==0){
    
                $hashedPassword = password_hash($form_password, PASSWORD_DEFAULT);
    
                $savetoDb=$connection->prepare("INSERT INTO `user_details` (`email`, `username`, `password`) VALUES (?,?,?);");
                $savetoDb->bind_param("sss",$form_email,$form_username,$hashedPassword);
                $save_result=$savetoDb->execute();
                if(!$savetoDb)
                    $validation_result["dbValidation"]="Something went wrong. Please try again !";
                else
                    $ajax_response["success"]=true;
            }
    
            else{
                $duplicate = mysqli_fetch_assoc($result);

                if($form_username==$duplicate['username'])
                    $validation_result["dbValidation"]="Username already exists!";
                else
                    $validation_result["dbValidation"]="Email Address already exists!";


            }
        }
        else    
            $validation_result["dbValidation"]="Something went wrong. Please try again !";
        
        
    }
if($ajax_response["success"]==false)
    $ajax_response["validations"]=$validation_result;

echo(json_encode($ajax_response));
exit();
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

    <form method="post" action="./register.php" class="flex-col-direction" id="register-form">
        <h3> REGISTER </h3>

        <input type="email" placeholder="Email" name="email" required>
        <span class="validate email-err"></span>

        <input type="text" placeholder="Username" name="username" required>
        <span  class="validate username-err"></span>

        <input type="password" placeholder="Password" name="password" required>
        <span  class="validate password-err"></span>

        <input type="password" placeholder="Confirm Password" name="c-password" required>
        <span  class="validate c-password-err"></span>

        <button type="submit" id="form-submit">REGISTER</button>

        <span class="validate dbValidation"></span>

        <a href="./login.php">Already a User?</a>

    </form>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="./index.js"></script>

</body>
</html>
