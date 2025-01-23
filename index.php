<?php
include 'authenticator.php';
$pageTitle = 'Home';

// Include the header
include 'header.php';
?>

<div class="container my-5 py-5 text-center">
    <?php include 'message.php' ?>

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