<?php
$pageTitle = 'Update Profile | EMS';
include '../layout/header.php'; // Include the header
include '../auth/isLogin.php';
?>

<div>
    <div id="form_card" class="card w-50 mx-auto shadow px-3">
        <div class="card-body">
            <h3 class="card-title py-3">My Profile</h3>
            <form action="update_profile_action.php" method="POST" class="">
                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Full Name</span>
                    <input type="text" class="form-control" name="name" value="<?php echo isset($form_data['name']) ? htmlspecialchars($form_data['name']) : htmlspecialchars($_SESSION['name']); ?>" required>
                </div>
                <?php if (isset($errors['name'])): ?>
                    <span class="error text-danger"><?php echo $errors['name']; ?></span>
                <?php endif; ?>

                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Email</span>
                    <input type="email" class="form-control" name="email" value="<?php echo isset($form_data['email']) ? htmlspecialchars($form_data['email']) : htmlspecialchars($_SESSION['email']); ?>" required>
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

                <button type="submit" required class="btn btn-success col-3 my-3">Update</button>
            </form>
        </div>
    </div>
</div>

<?php
// Include the footer
include '../layout/footer.php';
unset($_SESSION['info']);  // Clear the info message after displaying
unset($_SESSION['errors']);  // Clear the error message after displaying
unset($_SESSION['success']);  // Clear the success message after displaying
?>