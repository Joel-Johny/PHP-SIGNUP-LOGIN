<?php
function validations($form_email,$form_username,$form_password,$form_c_password){

    $error_email=array();
    $error_username=array();
    $error_password=array();

    if (empty($form_email)) 
        array_push($error_email,"Email address can't be empty");
    if (filter_var($error_email, FILTER_VALIDATE_EMAIL))
        array_push($validations_array["email"],"Please enter valid email address");

    if(empty($form_username)) 
        array_push($error_username,"Username can't be empty");
    if(strlen($form_username)<4)
        array_push($error_username,"Minimum length of username should be 4");

    if(strlen($form_password)<8)
        array_push($error_password,"Minimum password length should be 8");
    if($form_password!=$form_c_password)
        array_push($error_password,"Passwords do not match");
    
    $validations_array=array(
        "email"=>$error_email,
        "username"=>$error_username,
        "password"=>$error_password,
        "error_count"=> (count($error_email)+count($error_username)+count($error_password))
    );

    return $validations_array;
}

?>