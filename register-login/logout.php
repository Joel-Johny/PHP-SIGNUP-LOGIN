<?php

if (isset($_COOKIE['PHPSESSID'])) {
    $params = session_get_cookie_params();
    setcookie('PHPSESSID', '', time() - 100, $params['path'], $params['domain']);

    session_unset();
    session_destroy();
    

}
header('Location: login.php');
exit();
?>