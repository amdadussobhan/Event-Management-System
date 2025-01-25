<?php
session_start();  // Start the session to store error messages and form data

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Initialize an array to store error messages
    $errors = [];

    // Validation checks
    if (empty($name))
        $errors['name'] = 'Name fields is required.';
    if (empty($email))
        $errors['email'] = 'Email fields is required.';
    if (empty($password))
        $errors['password'] = 'Password fields are required.';

    // If there are validation errors, store them in the session and redirect back
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;  // Store form data (except password)
        header('Location: event_register_page.php?event_id=' . $_POST['event_id']);  // Redirect back to the form
        exit();
    }

    // If no errors, proceed with the registration logic
    include '../auth/db_connect.php';

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0 and empty($_SESSION['user_id'])) {
        $errors['email'] = 'This email is already registered. Please try by another email.';
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header('Location: event_register_page.php?event_id=' . $_POST['event_id']);
        $stmt->close();
        $conn->close();
        exit();
    }

    if (empty($_SESSION['user_id'])) {
        // Hash the password securely
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Insert the user into the database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password_hash);
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = "user";
            $stmt->close();
        }
    }

    // Insert the attendees into the database
    $date = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO attendees (event_id, user_id, registration_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $_POST['event_id'], $_SESSION['user_id'], $date);

    if ($stmt->execute()) {
        // Store success message in the session
        $_SESSION['success'] = "Event Registration successful!. Thank you for joining.";
        unset($_SESSION['errors']);  // Clear the errors message after successfully registered
        unset($_SESSION['form_data']);  // Clear the form data after successfully registered
        header('Location: my_events_page.php');  // Redirect to the home page

        $stmt->close();
        $conn->close();
        exit();
    }
}
