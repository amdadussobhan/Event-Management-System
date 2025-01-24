<?php
session_start();  // Start the session

// Check if the user is logged in as admin.
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] != 'admin') {
        // If the user is not logged in as admin, redirect to the Home page.
        header('Location: events/my_events.php');
        exit();  // Stop further execution of the script
    }
}else{
    header('Location: auth/login_form.php');
    exit();  // Stop further execution of the script
}

// If the user is logged in, continue to the home page content
?>