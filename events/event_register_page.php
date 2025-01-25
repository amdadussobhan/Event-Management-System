<?php
$pageTitle = 'Register Event | EMS';

// Include the header and message
include '../layout/header.php';

// If the user is logged in, continue to the home page content
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

// Check if the user is logged in by verifying if 'user_id' is set
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == "admin") {
        $_SESSION['info'] = "As a admin you are not allowed to register any event.";
        header('Location: event_details_page.php?event_id='.$_GET['event_id']);
        exit();  // Stop further execution of the script
    }
}
?>

<div>
    <div class="card w-50 mx-auto shadow px-3">
        <div class="card-body">
            <h3 class="card-title py-3">Complete Event Registration</h3>
            <form action="event_register_action.php" method="POST" class="">
                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Full Name</span>
                    <input type="text" class="form-control" name="name" <?php echo (isset($_SESSION['name']) ? "readonly" : ''); ?> value="<?php echo isset($form_data['name']) ? htmlspecialchars($form_data['name']) : (isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : ''); ?>" required>
                </div>
                <?php if (isset($errors['name'])): ?>
                    <span class="error text-danger"><?php echo $errors['name']; ?></span>
                <?php endif; ?>

                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Email</span>
                    <input type="email" class="form-control" name="email" <?php echo (isset($_SESSION['email']) ? "readonly" : ''); ?> value="<?php echo isset($form_data['email']) ? htmlspecialchars($form_data['email']) : (isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''); ?>" required>
                </div>
                <?php if (isset($errors['email'])): ?>
                    <span class="error text-danger"><?php echo $errors['email']; ?></span>
                <?php endif; ?>

                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Password</span>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <?php if (isset($errors['password'])): ?>
                    <span class="error text-danger"><?php echo $errors['password']; ?></span>
                <?php endif; ?>

                <!-- Hidden input for the event ID and ex_cover_photo -->
                <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($_GET['event_id']); ?>">
                <button type="submit" required class="btn btn-success col-3 my-3">Submit</button>
            </form>
        </div>
    </div>

</div>

<!-- Include the footer -->
<?php
include '../layout/footer.php';
unset($_SESSION['registered']);  // Clear the info message after displaying
unset($_SESSION['errors']);  // Clear the error message after displaying
unset($_SESSION['info']);  // Clear the info message after displaying
unset($_SESSION['success']);  // Clear the success message after displaying
unset($_SESSION['form_data']);  // Clear the success message after displaying
?>