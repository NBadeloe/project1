<?php

// initialize session
session_start();

// unset all session variables. Not sure which? Check the login method!
$_SESSION = [];

// destroy the session
session_destroy();

// user should be redirected to the login form on logout
header('location: index.php');
exit;
?>