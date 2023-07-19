<?php
function validations($form_email,$form_username,$form_password,$form_c_password){

    $error_email="";
    $error_username="";
    $error_password="";
    $count=0;

    if (empty($form_email)){
        $error_email="Email address can't be empty";
        $count++;
    }
    elseif (filter_var($error_email, FILTER_VALIDATE_EMAIL)){
        $error_email="Please enter valid email address";
        $count++;
    }

    if(empty($form_username)) {
        $error_username="Username can't be empty";
        $count++;
    }
    elseif(strlen($form_username)<4){
        $error_username="Minimum length of username should be 4";
        $count++;
    }

    if(strlen($form_password)<8){
        $error_password="Minimum password length should be 8";
        $count++;
    }
    elseif($form_password!=$form_c_password){
        $error_password="Passwords do not match";
        $count++;
    }

    
    $validations_array=array(
        "email"=>$error_email,
        "username"=>$error_username,
        "password"=>$error_password,
        "error_count"=> $count
    );

    return $validations_array;
}

?>