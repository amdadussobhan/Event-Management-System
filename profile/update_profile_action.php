<?php
session_start();  // Start the session to store error messages and form data
include '../auth/isLogin.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    // Initialize an array to store error messages
    $errors = [];

    // Validation checks
    if (empty($name))
        $errors['name'] = 'Name field is required.';
    if (empty($email))
        $errors['email'] = 'Email field is required.';
    
    if (empty($password1) || empty($password2))
        $errors['password'] = 'Both password fields are required.';
    elseif ($password1 != $password2)
        $errors['password'] = 'Passwords do not match.';

    // If there are validation errors, store them in the session and redirect back
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;  // Store form data (except password)
        header('Location: update_profile_page.php');  // Redirect back to the form
        exit();
    }

    // If no errors, proceed with the registration logic
    include '../auth/db_connect.php';

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? and id != ?");
    $stmt->bind_param("si", $email, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $errors['email'] = 'This email is already registered. Please try by another email.';
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header('Location: update_profile_page.php');
        $stmt->close();
        $conn->close();
        exit();
    } else {
        // Hash the password securely
        $password_hash = password_hash($password1, PASSWORD_BCRYPT);

        // Update the user into the database
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password_hash = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $password_hash, $_SESSION['user_id']);

        if ($stmt->execute()) {
            // Store success message in the session
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['success'] = "Profile update successfuly.";
            unset($_SESSION['errors']);  // Clear the errors message after successfully updated
            unset($_SESSION['form_data']);  // Clear the form data after successfully updated
        } else {
            $errors['info'] = 'Something went wrong, Try again.';
            $_SESSION['errors'] = $errors;
        }
        
        header('Location: update_profile_page.php');
        $stmt->close();
        $conn->close();
        exit();
    }
}
