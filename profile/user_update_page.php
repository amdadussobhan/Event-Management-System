<?php
$pageTitle = 'Update User Profile | EMS';
include '../layout/header.php'; // Include the header
include '../auth/isLogin.php';
include '../auth/isAdmin.php';

// If the user is logged in as admin, continue
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

$stmt = $conn->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $_GET['user_id']);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $name, $email, $role);
$stmt->fetch();
$conn->close();

if ($stmt->num_rows > 0):
    $stmt->close();
?>
    <div>
        <div id="form_card" class="card w-50 mx-auto shadow">
            <div class="card-body mx-3">
                <h3 class="card-title py-3">Update User Profile</h3>
                <form action="user_update_action.php" method="POST" enctype="multipart/form-data">
                    <div class="col input-group my-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">User Name</span>
                        <input type="text" class="form-control" name="name" value="<?php echo isset($form_data['name']) ? htmlspecialchars($form_data['name']) : (!empty($name) ? htmlspecialchars($name) : ''); ?>" required>
                    </div>
                    <?php if (isset($errors['name'])): ?>
                        <span class="error text-danger"><?php echo $errors['name']; ?></span>
                    <?php endif; ?>

                    <div class="col input-group my-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Email Address</span>
                        <input type="email" class="form-control" name="email" value="<?php echo isset($form_data['email']) ? htmlspecialchars($form_data['email']) : (!empty($email) ? htmlspecialchars($email) : ''); ?>" required>
                    </div>
                    <?php if (isset($errors['email'])): ?>
                        <span class="error text-danger"><?php echo $errors['email']; ?></span>
                    <?php endif; ?>

                    <div class="col input-group my-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">User Role</span>
                        <select class="form-select" name="role" required>
                            <option value="user" <?php echo isset($form_data['role']) && $form_data['role'] == 'user' ? 'selected' : (!empty($role) && $role == 'user' ? 'selected' : ''); ?>>user</option>
                            <option value="admin" <?php echo isset($form_data['role']) && $form_data['role'] == 'admin' ? 'selected' : (!empty($role) && $role == 'admin' ? 'selected' : ''); ?>>admin</option>
                        </select>
                    </div>      
                    <?php if (isset($errors['role'])): ?>
                        <span class="error text-danger"><?php echo $errors['role']; ?></span>
                    <?php endif; ?>              

                    <!-- Hidden input for the event ID and ex_cover_photo -->
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_GET['user_id']); ?>">
                    <button type="submit" required class="btn btn-success col-3 my-3">Update</button>
                </form>
            </div>
        </div>
    </div>
<?php
else:
    $errors['error'] = "User not found. Something went wrong. Please try again.";
    header('Location: userprofile_list_page.php');
    $conn->close();
    exit();
endif;

// Include the footer
include '../layout/footer.php';
unset($_SESSION['info']);  // Clear the info message after displaying
unset($_SESSION['errors']);  // Clear the error message after displaying
unset($_SESSION['success']);  // Clear the success message after displaying
unset($_SESSION['form_data']);  // Clear the success message after displaying
?>