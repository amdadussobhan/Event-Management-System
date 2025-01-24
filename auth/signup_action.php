<?php
session_start();  // Start the session to store error messages and form data

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    // Initialize an array to store error messages
    $errors = [];

    // Validation checks
    if (empty($name))
        $errors['name'] = 'Name fields is required.';
    if (empty($email))
        $errors['email'] = 'Email fields is required.';
    
    if (empty($password1) || empty($password2))
        $errors['password'] = 'Both password fields are required.';
    elseif ($password1 != $password2)
        $errors['password'] = 'Passwords do not match.';

    // If there are validation errors, store them in the session and redirect back
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;  // Store form data (except password)
        header('Location: signup_form.php');  // Redirect back to the form
        exit();
    }

    // If no errors, proceed with the registration logic
    include 'db_connect.php';

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $errors['email'] = 'This email is already registered. Please try by another email.';
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header('Location: signup_form.php');
        $stmt->close();
        $conn->close();
        exit();
    } else {
        // Hash the password securely
        $password_hash = password_hash($password1, PASSWORD_BCRYPT);

        // Insert the user into the database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password_hash);

        if ($stmt->execute()) {
            // Store success message in the session
            $_SESSION['success'] = "Registration successful!. You can now log in.";
            unset($_SESSION['errors']);  // Clear the errors message after successfully registered
            unset($_SESSION['form_data']);  // Clear the form data after successfully registered
            header('Location: login_form.php');  // Redirect to the home page
        } else {
            $errors['info'] = 'Something went wrong, Try again.';
            $_SESSION['errors'] = $errors;
            header('Location: signup_form.php');
        }
        
        $stmt->close();
        $conn->close();
        exit();
    }
}
