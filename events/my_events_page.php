<?php
$pageTitle = 'My Events | EMS';

// Include the header & message
include '../layout/header.php';
include '../auth/isLogin.php';

$stmt = $conn->prepare("SELECT e.id, e.title, e.date, e.max_capacity FROM events as e INNER JOIN attendees as a ON e.id = a.event_id WHERE a.user_id = ? ORDER BY date ASC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="text-center">
    <h4 class="pb-3">My Event List</h4>
</div>

<div class="text-center">
    <table class="table table-striped table-bordered table-hover">
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
            <?php $SL = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $SL++; ?></td>
                    <td><a href="event_details_page.php?event_id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['max_capacity']; ?></td>
                    <td>00</td>
                    <td>
                        <a href="event_details_page.php?event_id=<?php echo $row['id']; ?>" class="mx-2 px-5"><i class="fa-solid fa-eye pe-2"></i>view</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
$stmt->close();
$conn->close();

// Include the footer
include '../layout/footer.php';
unset($_SESSION['info']);  // Clear the info message after displaying
unset($_SESSION['errors']);  // Clear the error message after displaying
unset($_SESSION['success']);  // Clear the success message after displaying
unset($_SESSION['form_data']);  // Clear the form data message after displaying
?>
