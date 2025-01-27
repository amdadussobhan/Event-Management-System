<?php
session_start();
include '../auth/isLogin.php';

// Check if the event ID is provided
if (isset($_GET['event_id'])) {
    include '../auth/db_connect.php'; // Include database connection

    // First, delete attendees associated with this event (if necessary)
    $stmt = $conn->prepare("DELETE FROM attendees WHERE event_id = ? and user_id = ?");
    $stmt->bind_param("ii", $_GET['event_id'], $_SESSION['user_id']);
    $stmt->execute();

    if ($stmt->execute()) {
        // If the event is successfully cancel, redirect with a success message
        $_SESSION['success'] = "Event Cancel successfully!.";
    } else {
        // If there was an error, show an error message
        $_SESSION['info'] = "Oops!. Event does not cancel. Please try again";
    }

} else {
    $errors['info'] = "Oops!. Something went wrong. Please try again";
    $_SESSION['errors'] = $errors;
}

$stmt->close();
$conn->close();
header("Location: my_events_page.php");
exit();