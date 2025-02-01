<?php
$pageTitle = 'User List | EMS';
include '../layout/header.php'; // Include the header
include '../auth/isLogin.php';
include '../auth/isAdmin.php';

$stmt = $conn->prepare("SELECT id, name, email, role, created_at FROM users ORDER BY name");
$stmt->execute();
$result = $stmt->get_result();
?>

<div id="table_size">
    <h4 class="pb-3">All User List</h4>
    <table id="table_font" class="table table-striped table-bordered table-hover text-center align-middle">
        <thead class="table-info">
            <tr>
                <th>SL</th>
                <th>Full Name</th>
                <th>Email Address</th>
                <th>User Role</th>
                <th>Register Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php $SL = 1;
            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $SL++; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td><?php echo $row['created_at']; ?>
                    </td>
                    <td>
                        <a href="user_update_page.php?user_id=<?php echo $row['id']; ?>" class="px-2 mx-2 text-secondary"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="user_delete_action.php?user_id=<?php echo $row['id']; ?>" class="px-2 mx-2 text-danger" onclick="return confirm('Are you sure you want to delete this user.?');"><i class="fa-solid fa-trash-can"></i></a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Include the footer -->
<?php
$stmt->close();
$conn->close();

// Include the footer
include '../layout/footer.php';
unset($_SESSION['info']);  // Clear the info message after displaying
unset($_SESSION['errors']);  // Clear the error message after displaying
unset($_SESSION['success']);  // Clear the success message after displaying
?>