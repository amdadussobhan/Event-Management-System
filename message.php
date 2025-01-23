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
