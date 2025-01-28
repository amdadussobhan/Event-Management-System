<?php
$pageTitle = 'Create Event | EMS';

// Include the header
include '../layout/header.php';
include '../auth/isLogin.php';

// If the user is logged in as admin, continue
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
?>

<div>
    <div class="card w-50 mx-auto shadow">
        <div class="card-body mx-3">
            <h3 class="card-title py-3">Create New Event</h3>
            <form action="event_create_action.php" method="POST" enctype="multipart/form-data">
                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Event Title</span>
                    <input type="text" class="form-control" name="title" value="<?php isset($form_data['title']) ? htmlspecialchars($form_data['title']) : ''; ?>" required>
                </div>
                <?php if (isset($errors['title'])): ?>
                    <span class="error text-danger"><?php echo $errors['title']; ?></span>
                <?php endif; ?>

                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Event Date</span>
                    <input type="date" class="form-control" name="date" value="<?php isset($form_data['date']) ? htmlspecialchars($form_data['date']) : ''; ?>" required>
                </div>
                <?php if (isset($errors['date'])): ?>
                    <span class="error text-danger"><?php echo $errors['date']; ?></span>
                <?php endif; ?>

                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Max Capacity</span>
                    <input type="number" class="form-control" name="max_capacity" min="1" step="1" value="<?php isset($form_data['max_capacity']) ? htmlspecialchars($form_data['max_capacity']) : ''; ?>" required>
                </div>
                <?php if (isset($errors['max_capacity'])): ?>
                    <span class="error text-danger"><?php echo $errors['max_capacity']; ?></span>
                <?php endif; ?>

                <div class="col input-group my-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Description</span>
                    <textarea rows="4" class="form-control" name="description" required> <?php isset($form_data['description']) ? htmlspecialchars($form_data['description']) : ''; ?> </textarea>
                </div>
                <?php if (isset($errors['description'])): ?>
                    <span class="error text-danger"><?php echo $errors['description']; ?></span>
                <?php endif; ?>
                
                <div class="col input-group my-3">
                    <input type="file" class="form-control" name="cover_photo" accept="image/*" required>
                </div>
                <?php if (isset($errors['cover_photo'])): ?>
                    <span class="error text-danger"><?php echo $errors['cover_photo']; ?></span>
                <?php endif; ?>

                <button type="submit" required class="btn btn-success col-3 my-3">Create</button>
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
unset($_SESSION['form_data']);  // Clear the success message after displaying
?>