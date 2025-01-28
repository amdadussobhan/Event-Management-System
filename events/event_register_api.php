<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    include '../auth/db_connect.php';

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0 && empty($_SESSION['user_id'])) {
        $errors['email'] = 'This email is already registered. Please try another email.';
        echo json_encode(['errors' => $errors]);
        $stmt->close();
        $conn->close();
        exit();
    }

    $stmt->close();

    if (empty($_SESSION['user_id'])) {        
        $password_hash = password_hash($password, PASSWORD_BCRYPT); // Hash the password
        
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

    // Insert the attendee into the database
    $date = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO attendees (event_id, user_id, registration_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $_POST['event_id'], $_SESSION['user_id'], $date);

    if ($stmt->execute())
        echo json_encode(['success' => "Event Registration successful! Thank you for joining."]);
    else
        echo json_encode(['error' => "Event Registration failed. Please try again."]);

    $stmt->close();
    $conn->close();
}
