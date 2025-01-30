<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    include '../auth/db_connect.php';

    // Initialize an array to store error messages
    $errors = [];

    // Validation checks
    if (empty($name))
        $errors['name'] = 'Name field is required.';
    if (empty($email))
        $errors['email'] = 'Email field is required.';
    if (empty($password))
        $errors['password'] = 'Password field are required.';

    // If there are validation errors, store them in the session and redirect back
    if (!empty($errors)) {
        echo json_encode(['errors' => $errors]);
        exit();
    }

    if (empty($_SESSION['user_id'])) {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors['email'] = 'This email is already registered ! Please try another email.';
            echo json_encode(['errors' => $errors]);
            $stmt->close();
            $conn->close();
            exit();
        }

        $stmt->close();
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
    } else {
        // Check if this user already registered for this event
        $stmt = $conn->prepare("SELECT id FROM attendees WHERE event_id = ? and user_id = ?");
        $stmt->bind_param("ii", $_POST['event_id'], $_SESSION['user_id']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors['email'] = 'You are already registered ! Please check another event.';
            echo json_encode(['errors' => $errors]);
            $stmt->close();
            $conn->close();
            exit();
        }

        $stmt->close();
        // Check if the user exists
        $stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($password_hash);
        $stmt->fetch();
        $stmt->close();

        if (!password_verify($password, $password_hash)) {
            $errors['password'] = 'Password does not match ! Please try again.';
            echo json_encode(['errors' => $errors]);
            $conn->close();
            exit();
        }
    }

    // Insert the attendee into the database
    $date = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO attendees (event_id, user_id, registration_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $_POST['event_id'], $_SESSION['user_id'], $date);

    if ($stmt->execute())
        echo json_encode(['success' => "Event Registration successful ! You can now login to see."]);
    else
        echo json_encode(['error' => "Event Registration failed ! Please try again."]);

    $stmt->close();
    $conn->close();
}
