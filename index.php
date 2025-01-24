<?php
include 'auth/verify_admin.php';
$pageTitle = 'Dashboard | EMS';

// Include the header & message

include __DIR__.'/layout/header.php';
include __DIR__.'/layout/message.php';

// Fetch events from the database
include __DIR__.'/auth/db_connect.php';
$stmt = $conn->prepare("
    SELECT u.name, u.email, e.title
    FROM attendees as a
    INNER JOIN users as u ON a.user_id = u.id
    INNER JOIN events as e ON a.event_id = e.id
    ORDER BY a.id DESC
");

$stmt->execute();
$result = $stmt->get_result();
?>

<div class="row ms-5 ps-5">
    <div class="col ms-5 ps-5">
        <div class="card w-50 ms-5">
            <div class="card-body">
                <h4 class="card-title">Total Participant</h4>
                <h4 class="card-title">555</h4>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card w-50">
            <div class="card-body">
                <h4 class="card-title">Total Events</h4>
                <h4 class="card-title">555</h4>
            </div>
        </div>
    </div>
</div>

<div>
    <h5 class="pb-3" style="float: left;">Recent Participants</h5>
    <h5 class="pb-3" style="float: right;"><a href="events/event_form.php" class="text-decoration-none pe-2"><i class="fa-solid fa-plus pe-2"></i>Create New Event</a></h5>
</div>

<div class="text-center">
    <table class="table table-striped table-bordered table-hover">
        <thead class="table-info">
            <tr>
                <th>Participants Name</th>
                <th>Email Address</th>
                <th>Event Title</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
$stmt->close();
$conn->close();

// Include the footer
include 'layout/footer.php';
unset($_SESSION['info']);  // Clear the info message after displaying
unset($_SESSION['errors']);  // Clear the error message after displaying
unset($_SESSION['success']);  // Clear the success message after displaying
?>