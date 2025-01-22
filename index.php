<?php
$pageTitle = 'Home';
session_start();  // Start the session to store error messages and form data

// Include the header
include 'header.php';
?>

<div class="container my-5 py-5 text-center">
    <?php
    // Displaying message if it exists    
    if (isset($_SESSION['success'])) {
        echo "<h4 class='text-success pb-4'>" . $_SESSION['success'] . "</h4>";
    }

    if (isset($_SESSION['info'])) {
        echo "<h4 class='text-warning pb-4'>" . $_SESSION['info'] . "</h4>";
    }

    if (isset($errors['error'])) {
        echo "<h4 class='text-danger pb-4'>" . $errors['error'] . "</h4>";
    }
    ?>

    <h2>Welcome to the Event Management System</h2>
    <p>This is a platform where you can manage and attend events effortlessly.</p>
</div>

<!-- Include the footer -->
<?php
include 'footer.php';
unset($_SESSION['info']);  // Clear the info message after displaying
unset($_SESSION['errors']);  // Clear the error message after displaying
unset($_SESSION['success']);  // Clear the success message after displaying
?>