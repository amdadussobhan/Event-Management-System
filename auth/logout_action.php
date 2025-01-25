<?php
session_start();  // Start the session to store error messages and form data
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the login page or home page
header('Location: login_form.php');
exit();
?>