<?php
$pageTitle = 'Login | EMS';

// Include header
include __DIR__ . '/../layout/header.php';

// Check if the user is logged in by verifying if 'user_id' is set
if (isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page
    $_SESSION['info'] = "You are already loged in. If you want to login another account, logout first.";
    header('Location: ' . BASE_URL . 'index.php');
    exit();  // Stop further execution of the script
}
?>

<div>
    <div id="form_card" class="card w-50 mx-auto shadow px-3">
        <div class="card-body">
            <h3 class="card-title py-3">Login to EMS</h3>
            <form action="login_action.php" method="POST" class="">
                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Email</span>
                    <input type="email" class="form-control" name="email" value="<?php echo isset($form_data['email']) ? htmlspecialchars($form_data['email']) : ''; ?>" required>
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
                    <br>
                <?php endif; ?>

                <button type="submit" required class="btn btn-success col-3 my-3">Login</button>
            </form>
        </div>
    </div>
</div>

<!-- Include the footer -->
<?php
include '../layout/footer.php';
unset($_SESSION['info']);  // Clear the info message after displaying
unset($_SESSION['errors']);  // Clear the error message after displaying
unset($_SESSION['success']);  // Clear the success message after displaying
unset($_SESSION['form_data']);  // Clear the form data message after displaying
?>