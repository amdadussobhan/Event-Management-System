<?php
$pageTitle = 'ALL Events | EMS';

// Include the header
include '../layout/header.php';

$stmt = $conn->prepare("SELECT id, title, date, max_capacity, description FROM events ORDER BY date ASC");
$stmt->execute();
$result = $stmt->get_result();

if (isset($_SESSION['role'])): ?>
    <div>
        <h5 class="pb-3" style="float: left;">All Event List</h5>
        <h5 class="pb-3" style="float: right;"><a href="event_create_page.php" class="text-decoration-none pe-2"><i class="fa-solid fa-plus pe-2"></i>Create New Event</a></h5>
    </div>
<?php else: ?>
    <div class="text-center">
        <h4 class="pb-3">All Event List</h4>
    </div>
<?php endif; ?>

<div class="text-center">
    <table class="table table-striped table-bordered table-hover text-center">
        <thead class="table-info">
            <tr>
                <th>SL</th>
                <th>Event Title</th>
                <th>Date</th>
                <th>Max Capacity</th>
                <th>Participants</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php $SL = 1;
            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $SL++; ?></td>
                    <td><a href="event_details_page.php?event_id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['max_capacity']; ?></td>
                    <td>00</td>
                    <?php if (isset($_SESSION['role'])): ?>
                        <td>
                            <a href="event_details_page.php?event_id=<?php echo $row['id']; ?>" class="px-2 mx-2"><i class="fa-solid fa-eye"></i></a>
                            <a href="event_update_page.php?event_id=<?php echo $row['id']; ?>" class="px-2 mx-2 text-secondary"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="event_delete_action.php?event_id=<?php echo $row['id']; ?>" class="px-2 mx-2 text-danger" onclick="return confirm('Are you sure you want to delete this Event.?');"><i class="fa-solid fa-trash-can"></i></a>
                        </td>
                    <?php else: ?>
                        <td>
                            <a href="event_details_page.php?event_id=<?php echo $row['id']; ?>" class="mx-2"><i class="fa-solid fa-eye pe-2"></i>view</a>
                        </td>
                    <?php endif; ?>
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