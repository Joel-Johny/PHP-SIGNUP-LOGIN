<?php
function validations($form_email,$form_username,$form_password,$form_c_password){

    $validations_array=array();
    if (empty($form_email))
        $validations_array["email-err"]="Email address can't be empty";
    elseif(!filter_var($form_email, FILTER_VALIDATE_EMAIL))
        $validations_array["email-err"]="Please enter valid email address";

    if(empty($form_username)) 
        $validations_array["username-err"]="Username can't be empty";
    elseif(strlen($form_username)<4)
        $validations_array["username-err"]="Minimum length of username should be 4";

    if(strlen($form_password)<8)
        $validations_array["password-err"]="Minimum password length should be 8";
    if($form_password!=$form_c_password)
        $validations_array["c-password-err"]="Passwords do not match";

    return $validations_array;
}

?>