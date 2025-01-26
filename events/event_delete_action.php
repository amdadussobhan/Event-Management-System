<?php
session_start();

// Check if the event ID is provided
if (isset($_GET['event_id']) && $_SESSION['role'] == 'admin') {
    include '../auth/db_connect.php'; // Include database connection

    // First, delete attendees associated with this event (if necessary)
    $stmt = $conn->prepare("DELETE FROM attendees WHERE event_id = ?");
    $stmt->bind_param("i", $_GET['event_id']);
    $stmt->execute();
    $stmt->close();

    // Now, delete the event from the events table
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $_GET['event_id']);

    if ($stmt->execute()) {
        // If the event is successfully deleted, redirect with a success message
        $_SESSION['success'] = "Event deleted successfully!.";
    } else {
        // If there was an error, show an error message
        $_SESSION['info'] = "Oops!. Event does not delete. Please try again";
    }

} else {
    $errors['info'] = "Oops!. Something went wrong. Please try again";
    $_SESSION['errors'] = $errors;
}

$stmt->close();
$conn->close();
header("Location: event_list_page.php");
exit();