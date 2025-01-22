<?php
include 'authenticator.php';
$pageTitle = 'Create Event | EMS';

// Include the header
include 'header.php';

// Check if the user is logged in as admin.
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        // If the user is not logged in as admin, redirect to the Home page.
        $_SESSION['info'] = "You have dont access to create event.";
        header('Location: index.php');
        exit();  // Stop further execution of the script
    }
}

// If the user is logged in as admin, continue
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
?>

<div class="container my-5 text-center">
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

    <div class="card w-50 mx-auto">
        <div class="card-body">
            <h3 class="card-title py-3">Create New Event</h3>
            <form action="event_action.php" method="POST" class="">
                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Event Title</span>
                    <input type="text" class="form-control" name="title" value="<?php echo isset($form_data['title']) ? htmlspecialchars($form_data['title']) : ''; ?>" required>
                </div>
                <?php if (isset($errors['title'])): ?>
                    <span class="error text-danger"><?php echo $errors['title']; ?></span>
                <?php endif; ?>

                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Max Capacity</span>
                    <input type="text" class="form-control" name="capacity" value="<?php echo isset($form_data['capacity']) ? htmlspecialchars($form_data['capacity']) : ''; ?>" required>
                </div>
                <?php if (isset($errors['capacity'])): ?>
                    <span class="error text-danger"><?php echo $errors['capacity']; ?></span>
                <?php endif; ?>

                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Description</span>
                    <textarea type="text" rows="4" class="form-control" name="description" required> <?php echo isset($form_data['description']) ? htmlspecialchars($form_data['description']) : ''; ?> </textarea>
                </div>
                <?php if (isset($errors['description'])): ?>
                    <span class="error text-danger"><?php echo $errors['description']; ?></span>
                <?php endif; ?>
                
                <button type="submit" required class="btn btn-success col-3 my-3">Submit</button>
            </form>
        </div>
    </div>

</div>

<!-- Include the footer -->
<?php
include 'footer.php';
unset($_SESSION['info']);  // Clear the info message after displaying
unset($_SESSION['errors']);  // Clear the error message after displaying
unset($_SESSION['success']);  // Clear the success message after displaying
unset($_SESSION['form_data']);  // Clear the success message after displaying
?>