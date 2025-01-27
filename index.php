<?php
$pageTitle = 'Dashboard | EMS';
include 'layout/header.php'; // Include the header
include 'auth/isLogin.php';
include 'auth/isAdmin.php';

// Query to get the total number of events
$stmt = $conn->prepare("SELECT COUNT(*) AS total_events FROM events");
$stmt->execute();
$stmt->bind_result($total_events);
$stmt->fetch();
$stmt->close();

// Query to get the total number of participants
$stmt = $conn->prepare("SELECT COUNT(*) AS total_participants FROM attendees");
$stmt->execute();
$stmt->bind_result($total_participants);
$stmt->fetch();
$stmt->close();

//Get all participants list
$stmt = $conn->prepare("
    SELECT u.name, u.email, e.title
    FROM attendees as a
    INNER JOIN users as u ON a.user_id = u.id
    INNER JOIN events as e ON a.event_id = e.id
    ORDER BY a.id DESC
");

$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();
?>

<div class="row ms-5 ps-5">
    <div class="col ms-5 ps-5">
        <div class="card w-50 ms-5 shadow">
            <div class="card-body">
                <h4 class="card-title">Total Participant</h4>
                <h4 class="card-title"><a href="" class="text-decoration-none"><i class="fa-solid fa-user me-3"></i><?php echo $total_participants; ?></a></h4>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card w-50 shadow">
            <div class="card-body">
                <h4 class="card-title">Total Events</h4>
                <h4 class="card-title"><a href="events/event_list_page.php" class="text-decoration-none"><i class="fa-solid fa-calendar-days me-3"></i><?php echo $total_events; ?></a></h4>
            </div>
        </div>
    </div>
</div>

<div>
    <h5 class="pb-3" style="float: left;">Recent Participants</h5>
    <h5 class="pb-3" style="float: right;"><a href="events/event_create_page.php" class="text-decoration-none pe-2"><i class="fa-solid fa-plus pe-2"></i>Create New Event</a></h5>
</div>

<div class="text-center">
    <table class="table table-striped table-bordered table-hover text-center">
        <thead class="table-info">
            <tr>
                <th>SL</th>
                <th>Participants Name</th>
                <th>Email Address</th>
                <th>Event Title</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php $SL = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $SL++; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
// Include the footer
include 'layout/footer.php';
unset($_SESSION['info']);  // Clear the info message after displaying
unset($_SESSION['errors']);  // Clear the error message after displaying
unset($_SESSION['success']);  // Clear the success message after displaying
?>