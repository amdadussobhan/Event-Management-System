<?php
include 'verify_user.php';
$pageTitle = 'Event List | EMS';

// Include the header
include 'header.php';

// Fetch events from the database
include 'db_connect.php';
$stmt = $conn->prepare("SELECT title, date, max_capacity, description FROM events ORDER BY date ASC");
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container my-4">
    <?php include 'message.php' ?>
    <div>
        <h4 class="pb-3" style="float: left;">All Event List</h4>
        <h4 class="pb-3" style="float: right;"><a href="event_form.php" class="text-decoration-none pe-2"><i class="fa-solid fa-plus pe-3"></i>Create Event</a></h4>
    </div>
    <div class="text-center">
        <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Event Title</th>
                    <th>Date</th>
                    <th>Max Capacity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="w-25"><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['max_capacity']); ?></td>
                        <td>
                            <a href="event_details.php?event_id=<?php echo $row['title']; ?>" class="px-2 mx-2"><i class="fa-solid fa-eye"></i></a>
                            <a href="event_details.php?event_id=<?php echo $row['title']; ?>" class="px-2 mx-2 text-secondary"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="event_details.php?event_id=<?php echo $row['title']; ?>" class="px-2 mx-2 text-danger"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$stmt->close();
$conn->close();
?>