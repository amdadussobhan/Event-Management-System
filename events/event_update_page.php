<?php
$pageTitle = 'Update Event | EMS';

// Include the header
include '../layout/header.php';
include '../auth/isLogin.php';

// If the user is logged in as admin, continue
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

$stmt = $conn->prepare("SELECT id, title, date, max_capacity, description, cover_photo FROM events WHERE id = ?");
$stmt->bind_param("i", $_GET['event_id']);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $title, $date, $max_capacity, $description, $cover_photo);
$stmt->fetch();
$conn->close();

$_SESSION['cover_photo'] = $cover_photo;

if ($stmt->num_rows > 0):
    $stmt->close();
?>
    <div>
        <div class="card w-50 mx-auto shadow">
            <div class="card-body mx-3">
                <h3 class="card-title py-3">Update existing Event</h3>
                <form action="event_update_action.php" method="POST" enctype="multipart/form-data">
                    <div class="col input-group my-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Event Title</span>
                        <input type="text" class="form-control" name="title" value="<?php echo isset($form_data['title']) ? htmlspecialchars($form_data['title']) : (!empty($title) ? htmlspecialchars($title) : ''); ?>" required>
                    </div>
                    <?php if (isset($errors['title'])): ?>
                        <span class="error text-danger"><?php echo $errors['title']; ?></span>
                    <?php endif; ?>

                    <div class="col input-group my-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Event Date</span>
                        <input type="date" class="form-control" name="date" value="<?php echo isset($form_data['date']) ? htmlspecialchars($form_data['date']) : (!empty($date) ? htmlspecialchars($date) : ''); ?>" required>
                    </div>
                    <?php if (isset($errors['date'])): ?>
                        <span class="error text-danger"><?php echo $errors['date']; ?></span>
                    <?php endif; ?>

                    <div class="col input-group my-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Max Capacity</span>
                        <input type="number" min="1" step="1" class="form-control" name="max_capacity" value="<?php echo isset($form_data['max_capacity']) ? htmlspecialchars($form_data['max_capacity']) : (!empty($max_capacity) ? htmlspecialchars($max_capacity) : ''); ?>" required>
                    </div>
                    <?php if (isset($errors['max_capacity'])): ?>
                        <span class="error text-danger"><?php echo $errors['max_capacity']; ?></span>
                    <?php endif; ?>

                    <div class="col input-group my-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Description</span>
                        <textarea rows="6" class="form-control" name="description" required> <?php echo isset($form_data['description']) ? htmlspecialchars($form_data['description']) : (!empty($description) ? htmlspecialchars($description) : ''); ?> </textarea>
                    </div>
                    <?php if (isset($errors['description'])): ?>
                        <span class="error text-danger"><?php echo $errors['description']; ?></span>
                    <?php endif; ?>

                    <!-- File Upload for New Cover Photo -->
                    <div class="col input-group">
                        <input type="file" class="form-control" name="cover_photo" accept="image/*">
                    </div>
                    <?php if (isset($errors['cover_photo'])): ?>
                        <span class="error text-danger"><?php echo $errors['cover_photo']; ?></span>
                    <?php endif; ?>
                    <h6 class="text-muted">Leave blank to keep the current cover photo.</h6>

                    <!-- Current Cover Photo Preview -->
                    <div class="col mt-3">
                        <?php if (!empty($cover_photo)): ?>
                            <div>
                                <img src="/ems/<?php echo htmlspecialchars($cover_photo); ?>" alt="Cover Photo" class="shadow" style="width: 100%; height: 111px;">
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Hidden input for the event ID and ex_cover_photo -->
                    <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($_GET['event_id']); ?>">
                    <input type="hidden" name="ex_cover_photo" value="<?php echo htmlspecialchars($cover_photo); ?>">

                    <button type="submit" required class="btn btn-success col-3 my-3">Update</button>
                </form>
            </div>
        </div>
    </div>
<?php
else:
    $errors['error'] = "Events not found. Something went wrong. Please try again.";
    header('Location: event_list_page.php');
    $stmt->close();
    exit();  // Stop further execution of the script
endif;

// Include the footer
include '../layout/footer.php';
unset($_SESSION['info']);  // Clear the info message after displaying
unset($_SESSION['errors']);  // Clear the error message after displaying
unset($_SESSION['success']);  // Clear the success message after displaying
unset($_SESSION['form_data']);  // Clear the success message after displaying
?>