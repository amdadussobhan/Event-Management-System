<?php
$pageTitle = 'Sign up | EMS';

// Include the header
include 'header.php';
session_start();

// Check if the user is logged in by verifying if 'user_id' is set
if (isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page
    $_SESSION['info'] = "You are already loged in!. Logout first if you want to register another account.";
    header('Location: index.php');
    exit();  // Stop further execution of the script
}

// If the user is logged in, continue to the home page content
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
            <h3 class="card-title py-3">Complete Your Sign up</h3>
            <form action="register_action.php" method="POST" class="">
                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Full Name</span>
                    <input type="text" class="form-control" name="name" value="<?php echo isset($form_data['name']) ? htmlspecialchars($form_data['name']) : ''; ?>" required>
                </div>
                <?php if (isset($errors['name'])): ?>
                    <span class="error text-danger"><?php echo $errors['name']; ?></span>
                <?php endif; ?>

                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Email</span>
                    <input type="email" class="form-control" name="email" value="<?php echo isset($form_data['email']) ? htmlspecialchars($form_data['email']) : ''; ?>" required>
                </div>
                <?php if (isset($errors['email'])): ?>
                    <span class="error text-danger"><?php echo $errors['email']; ?></span>
                <?php endif; ?>

                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Password</span>
                    <input type="password" class="form-control" name="password1" required>
                </div>
                <?php if (isset($errors['password'])): ?>
                    <span class="error text-danger"><?php echo $errors['password']; ?></span>
                <?php endif; ?>

                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Confirm Password</span>
                    <input type="password" class="form-control" name="password2" required>
                </div>
                <?php if (isset($errors['password'])): ?>
                    <span class="error text-danger"><?php echo $errors['password']; ?></span>
                    <br>
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