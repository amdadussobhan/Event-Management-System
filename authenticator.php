<?php
session_start();  // Start the session

// Check if the user is logged in by verifying if 'user_id' is set
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page
    header('Location: login_form.php');
    exit();  // Stop further execution of the script
}
// If the user is logged in, continue to the home page content
?>