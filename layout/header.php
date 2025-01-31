<?php
session_start();
include __DIR__ . '/../auth/db_connect.php';
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Event Management System'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/ems/layout/responsive.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div id="main_div" class="container mt-2 text-center">
        <?php // Displaying message if it exists
        if (isset($_SESSION['success']))
            echo "<h4 id='success' class='text-success pb-4'>" . $_SESSION['success'] . "</h4>";

        if (isset($_SESSION['info']))
            echo "<h4 id='info' class='text-warning pb-4'>" . $_SESSION['info'] . "</h4>";

        if (isset($errors['error']))
            echo "<h4 id='error' class='text-danger pb-4'>" . $errors['error'] . "</h4>";
        ?>