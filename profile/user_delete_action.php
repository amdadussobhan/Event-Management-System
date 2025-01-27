<?php
session_start();
include '../auth/isLogin.php';
include '../auth/isAdmin.php';

// Check if the user ID is provided
if (isset($_GET['user_id'])) {
    // First, delete attendees associated with this event (if necessary)
    include '../auth/db_connect.php'; // Include database connection
    $stmt = $conn->prepare("DELETE FROM attendees WHERE user_id = ?");
    $stmt->bind_param("i", $_GET['user_id']);
    $stmt->execute();
    $stmt->close();

    // Now, delete the user from the users table
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $_GET['user_id']);

    if ($stmt->execute()) {
        // If the users is successfully deleted, redirect with a success message
        $_SESSION['success'] = "User deleted successfully!.";
    } else {
        // If there was an error, show an error message
        $_SESSION['info'] = "Oops!. User does not delete. Please try again";
    }

    $stmt->close();
    $conn->close();
} else {
    $errors['info'] = "Oops!. Something went wrong. Please try again";
    $_SESSION['errors'] = $errors;
}

header("Location: userprofile_list_page.php");
exit();