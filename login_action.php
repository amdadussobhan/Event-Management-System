<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Initialize an array to store error messages
    $errors = [];

    // Validation checks
    if (empty($email))
        $errors['email'] = 'Email is empty. Please enter valid email.';
    if (empty($password))
        $errors['password'] = 'Password is empty. Please enter valid password.';

    // If there are validation errors, store them in the session and redirect back
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;  // Store form data (except password)
        header('Location: login_form.php');  // Redirect back to the form
        exit();
    }

    // If no errors, proceed with the registration logic
    include 'db_connect.php';
    // Check if the user exists
    $stmt = $conn->prepare("SELECT id, name, email, password_hash, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $name, $email, $password_hash, $role);
    $stmt->fetch();
    $conn->close();

    if ($stmt->num_rows > 0) {
        $stmt->close();

        // Verify the password
        if (password_verify($password, $password_hash)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;
            header("Location: index.php");  // Redirect to the dashboard page
            exit();
        } else {
            $errors['error'] = "Email or Password is not correct. Please try again.";
        }
    } else {
        $stmt->close();
        $errors['error'] = "User not found!. Please Register first.";
    }

    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST;  // Store form data (except password)
    header("Location: login_form.php");  // Redirect to the login page
    exit();
}
