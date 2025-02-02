<?php
session_start();  // Start the session to store error messages and form data
include '../auth/isLogin.php';
include '../auth/isAdmin.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Initialize an array to store error messages
    $errors = [];

    // Validation checks
    if (empty($name))
        $errors['name'] = 'Name field is required.';

    // Validate email format
    if (empty($email))
        $errors['email'] = 'Email field is required.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors['email'] = "Invalid email format. Please enter a valid email address.";

    if (empty($role))
        $errors['role'] = 'Role field is required.';

    // If no errors, proceed with the registration logic
    include '../auth/db_connect.php';

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? and id != ?");
    $stmt->bind_param("si", $email, $_POST['user_id']);
    $stmt->execute();

    if ($stmt->num_rows > 0) {
        $errors['email'] = 'This email is already registered. Please try by another email.';
        $_SESSION['errors'] = $errors;
    }

    $stmt->close();

    // If there are validation errors, store them in the session and redirect back
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;  // Store form data (except password)
        header('Location: user_update_page.php?user_id' . $_POST['user_id']);  // Redirect back to the form
        exit();
    }

    // Update the user into the database
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $email, $role, $_POST['user_id']);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile update successfuly.";
        unset($_SESSION['errors']);  // Clear the errors message after successfully updated
        unset($_SESSION['form_data']);  // Clear the form data after successfully updated

        if ($_SESSION['user_id'] == $_POST['user_id']) {
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;
        }
    } else {
        $errors['info'] = 'Something went wrong, Try again.';
        $_SESSION['errors'] = $errors;
    }

    $stmt->close();
    $conn->close();
    header('Location: user_update_page.php?user_id' . $_POST['user_id']);
    exit();
}
