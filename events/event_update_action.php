<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] == 'admin') {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $max_capacity = $_POST['max_capacity'];
    $description = $_POST['description'];

    // Initialize an array to store error messages
    $errors = [];

    // Validation checks
    if (empty($title))
        $errors['title'] = 'Title field is required.';
    if (empty($date))
        $errors['date'] = 'Date field is required.';
    if (empty($max_capacity))
        $errors['max_capacity'] = 'Max capacity field is required.';
    if (empty($description))
        $errors['description'] = 'Description field is required.';

    // If there are validation errors, store them in the session and redirect back
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;  // Store form data (except password)
        header('Location: event_update_page.php');  // Redirect back to the form
        exit();
    }

    // Handle file upload
    $cover_photo = null; // Default to null if no file is uploaded

    if (isset($_FILES['cover_photo']) && $_FILES['cover_photo']['error'] == 0) {
        $target_dir = 'media/';

        // Check if the directory exists, if not, create it
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); // Creates the directory with appropriate permissions
        }

        $cover_photo = $target_dir . basename($_FILES["cover_photo"]["name"]);

        // Save the file to the server
        if (!move_uploaded_file($_FILES["cover_photo"]["tmp_name"], $cover_photo)) {
            die("Sorry, there was an error uploading your file.");
        }
    } else
        $cover_photo = $_POST['ex_cover_photo'];

    // If no errors, proceed with the event logic
    include '../auth/db_connect.php';

    // Insert the event into the database
    $stmt = $conn->prepare("UPDATE events SET title = ?, date = ?, max_capacity = ?, description = ?, cover_photo = ?, created_by = ? WHERE id = ?");
    $stmt->bind_param("ssissii", $title, $date, $max_capacity, $description, $cover_photo, $_SESSION['user_id'], $_POST['event_id']);

    if ($stmt->execute()) {
        // Store success message in the session
        $_SESSION['success'] = "Event update successfully!.";
        unset($_SESSION['errors']);  // Clear the errors message after successfully updated
        unset($_SESSION['form_data']);  // Clear the form data after successfully updated
        header('Location: event_list_page.php');  // Redirect to the event list page
    } else {
        $errors['info'] = 'Something went wrong, Please try again.';
        $_SESSION['errors'] = $errors;
        header('Location: event_update_page.php');
    }

    $stmt->close();
    $conn->close();
    exit();
}
