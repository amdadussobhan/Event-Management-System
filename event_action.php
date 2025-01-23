<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] == 'admin') {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $max_capacity = $_POST['max_capacity'];
    $description = $_POST['description'];
    $created_by = $_SESSION['user_id'];

    // Initialize an array to store error messages
    $errors = [];

    // Validation checks
    if (empty($title))
        $errors['title'] = 'Title is required.';
    if (empty($date))
        $errors['date'] = 'Date is required.';
    if (empty($max_capacity))
        $errors['max_capacity'] = 'Max capacity is required.';

    // If there are validation errors, store them in the session and redirect back
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;  // Store form data (except password)
        header('Location: event_form.php');  // Redirect back to the form
        exit();
    }
    
    // If no errors, proceed with the event logic
    include 'db_connect.php';

    // Insert the event into the database
    $stmt = $conn->prepare("INSERT INTO events (title, date, max_capacity, description, created_by) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $title, $date, $max_capacity, $description, $created_by);

    if ($stmt->execute()) {
        // Store success message in the session
        $_SESSION['success'] = "Event created successfully!.";
        unset($_SESSION['errors']);  // Clear the errors message after successfully registered
        unset($_SESSION['form_data']);  // Clear the form data after successfully registered
        header('Location: index.php');  // Redirect to the home page
    } else {
        $errors['info'] = 'Something went wrong, Try again.';
        $_SESSION['errors'] = $errors;
        header('Location: register_form.php');
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>
