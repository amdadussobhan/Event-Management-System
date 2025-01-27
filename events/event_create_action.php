<?php
session_start();
include '../auth/isLogin.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    // Handle file upload
    $cover_photo = null; // Default to null if no file is uploaded

    if (isset($_FILES['cover_photo']) && $_FILES['cover_photo']['error'] == 0) {
        $target_dir = dirname(__DIR__) . '/media/';

        // Check if the directory exists, if not, create it
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); // Creates the directory with appropriate permissions
        }

        $cover_photo = basename($_FILES["cover_photo"]["name"]);
        $cover_photo_path = 'media/' . $cover_photo;

        // Save the file to the server using the absolute path
        $target_file = $target_dir . $cover_photo;

        // Save the file to the server
        if (!move_uploaded_file($_FILES["cover_photo"]["tmp_name"], $target_file)) {
            die("Sorry, there was an error uploading your file.");
        }

        $cover_photo = $cover_photo_path;  // Store the relative path in the variable   
    }

    if (empty($cover_photo))
        $errors['cover_photo'] = 'Cover Photo field is required.';

    // If there are validation errors, store them in the session and redirect back
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;  // Store form data (except password)
        header('Location: event_create_page.php');  // Redirect back to the form
        exit();
    }

    // If no errors, proceed with the event logic
    include '../auth/db_connect.php';

    // Insert the event into the database
    $stmt = $conn->prepare("INSERT INTO events (title, date, max_capacity, description, cover_photo, created_by) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssissi", $title, $date, $max_capacity, $description, $cover_photo, $_SESSION['user_id']);

    if ($stmt->execute()) {
        // Store success message in the session
        $_SESSION['success'] = "Event created successfully!.";
        unset($_SESSION['errors']);  // Clear the errors message after successfully registered
        unset($_SESSION['form_data']);  // Clear the form data after successfully registered
        header('Location: event_list_page.php');  // Redirect to the home page
    } else {
        $errors['info'] = 'Something went wrong, Please try again.';
        $_SESSION['errors'] = $errors;
        header('Location: event_create_page.php');
    }

    $stmt->close();
    $conn->close();
    exit();
}
