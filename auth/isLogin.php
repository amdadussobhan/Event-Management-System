<?php
// Check if the user is logged in by verifying if 'user_id' is set redirect to the login page
if (!isset($_SESSION['role'])) {
    $errors['error'] = "You cant access this page without login.";
    $_SESSION['errors'] = $errors;
    header('Location: ../auth/login_form.php');
    exit();  // Stop further execution of the script
}
