<?php
include "./validations.php";

session_start();
if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit();
}

$dbCheck=false;
if($_SERVER["REQUEST_METHOD"]=="POST"){

    $form_username=$_POST["username"];
    $form_email=$_POST["email"];
    $form_password=$_POST["password"];
    $form_c_password=$_POST["c-password"];

    $validation_result=validations($form_email,$form_username,$form_password,$form_c_password);

    echo var_dump($validation_result)."<br>";
    // echo var_dump($validation_result["username"])."<br>";
    // echo var_dump($validation_result["password"])."<br>";
    // echo var_dump($validation_result["error_count"])."<br>";

    if($validation_result["error_count"]==0){

        include "./connectdb.php";
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
    
                $savetoDb=$connection->prepare("INSERT INTO `user_details` (`email`, `user_id`, `password`) VALUES (?,?,?);");
                $savetoDb->bind_param("sss",$form_email,$form_username,$hashedPassword);
                $save_result=$savetoDb->execute();
                if($savetoDb)
                    $dbCheck="Account created successfully.You can proceed to login !";
                else
                    $dbCheck="Something went wrong. Please try again !";

            }
    
            else{
                $duplicate = mysqli_fetch_assoc($result)['duplicate_source'];
                $dbCheck="$duplicate already exists!";
            }
        }
        else    
            $dbCheck="Something went wrong. Please try again !";
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
        <?php echo '<span class=\'validate\'>' . ((isset($validation_result["email"]))?$validation_result["email"]:"") . '</span>'; ?>

        <input type="text" placeholder="Username" name="username" required>
        <?php echo '<span class=\'validate\'>' . ((isset($validation_result["username"]))?$validation_result["username"]:"") . '</span>'; ?>

        <input type="password" placeholder="Password" name="password" required>
        <input type="password" placeholder="Confirm Password" name="c-password" required>
        <?php echo '<span class=\'validate\'>' .((isset($validation_result["password"]))?$validation_result["password"]:"") . '</span>'; ?>

        <button type="submit">REGISTER</button>

        <?php echo "<h5 class=result>$dbCheck</h5>";?>

        <a href="./login.php">Already a User?</a>

    </form>
    
</body>
</html>