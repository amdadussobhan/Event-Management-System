<?php
session_start();  // Start the session

// Check if the user is logged in as admin.
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] != 'admin') {
        // If the user is not logged in as admin, redirect to the Home page.
        $_SESSION['info'] = "You have dont access to create event.";
        header('Location: index.php');
        exit();  // Stop further execution of the script
    }
}
// If the user is logged in, continue to the home page content
?>